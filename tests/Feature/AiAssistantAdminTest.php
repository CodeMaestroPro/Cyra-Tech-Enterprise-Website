<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiAssistantAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_ai_assistant(): void
    {
        $this->get(route('admin.ai-assistant.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_ai_assistant(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.ai-assistant.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.ai-assistant.index')
            ->assertSee('Cyra AI')
            ->assertSee('Executive Chat')
            ->assertSee('Model Status')
            ->assertSee('Summarize company health');
    }

    public function test_admin_can_query_ai_assistant(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-assistant.query'), [
            'message' => 'Summarize company health',
            'prompt' => 'company-health',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => ['slug', 'content', 'generated_at'],
            ])
            ->assertJsonPath('data.slug', 'company-health');
    }

    public function test_ai_assistant_answers_platform_module_questions(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-assistant.query'), [
            'message' => 'How many platform modules are complete?',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.slug', 'platform-modules')
            ->assertSee('Platform module roadmap', false);
    }

    public function test_ai_assistant_answers_product_questions(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-assistant.query'), [
            'message' => 'What products does Cyra-Tech offer?',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.slug', 'products')
            ->assertSee('Cyra-Tech product suite', false);
    }

    public function test_ai_assistant_answers_external_cloud_questions(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('admin.ai-assistant.query'), [
            'message' => 'Explain cloud migration strategy for enterprises',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.slug', 'external-topic')
            ->assertSee('Cloud computing', false);
    }

    public function test_manager_can_view_ai_assistant(): void
    {
        $manager = User::factory()->create(['email' => 'manager-ai@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.ai-assistant.index'))->assertOk();
    }

    public function test_viewer_can_view_ai_assistant_with_dashboard_access(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-ai@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)
            ->get(route('admin.ai-assistant.index'))
            ->assertOk()
            ->assertSee('Cyra AI');
    }
}
