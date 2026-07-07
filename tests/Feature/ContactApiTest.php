<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_contact_api_returns_page_configuration(): void
    {
        $response = $this->getJson(route('api.contact.show'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'inquiry_types',
                    'offices',
                    'channels',
                    'form',
                    'support',
                ],
            ])
            ->assertJsonPath('data.hero.title', 'Ready to transform your enterprise?')
            ->assertJsonPath('data.inquiry_types.0.slug', 'sales');
    }

    public function test_contact_api_accepts_valid_inquiry_submission(): void
    {
        $response = $this->postJson(route('api.contact.store'), [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'company' => 'Helix Health',
            'inquiry_type' => 'partnership',
            'message' => 'Interested in exploring a strategic partnership.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'reference',
                    'message',
                ],
            ]);

        $this->assertDatabaseCount('contact_inquiries', 1);

        $inquiry = ContactInquiry::query()->first();

        $this->assertSame('Jordan Lee', $inquiry->name);
        $this->assertSame('partnership', $inquiry->inquiry_type);
    }

    public function test_contact_api_returns_validation_errors_for_invalid_payload(): void
    {
        $response = $this->postJson(route('api.contact.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'inquiry_type' => 'unknown',
            'message' => '',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'inquiry_type', 'message']);

        $this->assertDatabaseCount('contact_inquiries', 0);
    }
}
