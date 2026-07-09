<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_homepage_footer_renders_active_newsletter_form(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('Stay ahead with Cyra-Tech')
            ->assertSee('newsletter-signup', false)
            ->assertSee(route('newsletter.subscribe'), false)
            ->assertSee('data-newsletter-form', false)
            ->assertSee('type="email"', false)
            ->assertSee('type="submit"', false);
    }

    public function test_newsletter_subscription_creates_subscriber_and_redirects_with_success(): void
    {
        $response = $this->from(route('home'))->post(route('newsletter.subscribe'), [
            'email' => 'subscriber@example.com',
        ]);

        $response
            ->assertRedirect(route('home').'#newsletter-signup')
            ->assertSessionHas('newsletter_status', 'success')
            ->assertSessionHas('newsletter_message');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
            'status' => 'active',
            'source' => 'footer',
        ]);
    }

    public function test_duplicate_newsletter_subscription_returns_info_message(): void
    {
        NewsletterSubscriber::query()->create([
            'email' => 'existing@example.com',
            'status' => 'active',
            'source' => 'footer',
            'subscribed_at' => now(),
        ]);

        $response = $this->from(route('home'))->post(route('newsletter.subscribe'), [
            'email' => 'existing@example.com',
        ]);

        $response
            ->assertRedirect(route('home').'#newsletter-signup')
            ->assertSessionHas('newsletter_status', 'info');

        $this->assertDatabaseCount('newsletter_subscribers', 1);
    }

    public function test_newsletter_subscription_validation_errors_are_returned(): void
    {
        $response = $this->from(route('home'))->post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }
}
