<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_contact_page_renders_form_and_office_details(): void
    {
        $response = $this->get(route('contact'));

        $response
            ->assertOk()
            ->assertViewIs('contact.index')
            ->assertSee('Ready to transform your enterprise?')
            ->assertSee('Send us a message')
            ->assertSee('Global Offices')
            ->assertSee('Lagos, Nigeria')
            ->assertSee('sales@cyratech.com');
    }

    public function test_contact_form_submission_creates_inquiry_and_redirects_with_success(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Ada Okonkwo',
            'email' => 'ada@example.com',
            'company' => 'NovaBank',
            'phone' => '+234 800 000 0000',
            'inquiry_type' => 'sales',
            'message' => 'We would like to discuss a digital core modernization program.',
        ]);

        $response
            ->assertRedirect(route('contact'))
            ->assertSessionHas('success')
            ->assertSessionHas('reference');

        $this->assertDatabaseCount('contact_inquiries', 1);

        $inquiry = ContactInquiry::query()->first();

        $this->assertSame('Ada Okonkwo', $inquiry->name);
        $this->assertSame('sales', $inquiry->inquiry_type);
        $this->assertSame('pending', $inquiry->status);
        $this->assertNotNull($inquiry->reference);
    }

    public function test_contact_form_validation_errors_are_returned(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'inquiry_type' => 'invalid',
            'message' => '',
        ]);

        $response
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors(['name', 'email', 'inquiry_type', 'message']);

        $this->assertDatabaseCount('contact_inquiries', 0);
    }
}
