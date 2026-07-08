<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\CrmLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_crm_pipeline_api_requires_authentication(): void
    {
        $this->getJson(route('api.crm.index'))->assertUnauthorized();
    }

    public function test_crm_pipeline_api_returns_pipeline_for_admin(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.crm.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'summary' => [
                        'total',
                        'pipeline_value',
                        'won',
                        'high_priority',
                        'inbound_inquiries',
                    ],
                    'pipeline_stages',
                    'sources',
                    'priorities',
                    'stages',
                    'inbound_inquiries',
                ],
            ])
            ->assertJsonPath('data.summary.total', fn ($value) => $value >= 6);
    }

    public function test_crm_show_api_returns_lead_details(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.crm.show', 'CRM-SEED-001'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.reference', 'CRM-SEED-001')
            ->assertJsonPath('data.name', 'Amara Okafor');
    }

    public function test_crm_create_api_creates_lead(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $before = CrmLead::query()->count();

        $response = $this->actingAs($admin)->postJson(route('api.crm.store'), [
            'name' => 'API Lead',
            'email' => 'api.lead@example.com',
            'company' => 'API Ventures',
            'source' => 'referral',
            'pipeline_stage' => 'new',
            'priority' => 'high',
            'estimated_value' => 5000000,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'API Lead');

        $this->assertSame($before + 1, CrmLead::query()->count());
    }

    public function test_crm_stage_api_updates_pipeline_stage(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->patchJson(route('api.crm.stage', 'CRM-SEED-003'), [
            'pipeline_stage' => 'proposal',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.pipeline_stage', 'proposal');

        $this->assertDatabaseHas('crm_leads', [
            'reference' => 'CRM-SEED-003',
            'pipeline_stage' => 'proposal',
        ]);
    }

    public function test_crm_convert_inquiry_api_creates_linked_lead(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $inquiry = ContactInquiry::query()->create([
            'reference' => 'INQ-CRM-TEST-001',
            'name' => 'CRM Convert Test',
            'email' => 'convert@example.com',
            'company' => 'Convert Corp',
            'inquiry_type' => 'sales',
            'message' => 'Interested in enterprise solutions.',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->postJson(route('api.crm.inquiries.convert', $inquiry->id));

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'convert@example.com');

        $this->assertDatabaseHas('crm_leads', [
            'email' => 'convert@example.com',
            'contact_inquiry_id' => $inquiry->id,
            'source' => 'contact_form',
        ]);
    }
}
