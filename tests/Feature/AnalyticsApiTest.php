<?php

namespace Tests\Feature;

use App\Models\AnalyticsEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_analytics_dashboard_api_requires_authentication(): void
    {
        $this->getJson(route('api.analytics.dashboard'))->assertUnauthorized();
    }

    public function test_analytics_dashboard_api_returns_metrics_for_admin(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.analytics.dashboard'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'range_days',
                    'period',
                    'overview' => [
                        'page_views',
                        'module_views',
                        'unique_sessions',
                        'form_submissions',
                        'contact_inquiries',
                        'portal_logins',
                        'conversion_rate',
                    ],
                    'traffic_trend',
                    'top_pages',
                    'top_modules',
                    'lead_sources',
                    'platform_snapshot',
                    'insights',
                ],
            ])
            ->assertJsonPath('data.overview.page_views', fn ($value) => $value > 0);
    }

    public function test_analytics_event_api_accepts_valid_page_view(): void
    {
        $response = $this->postJson(route('api.analytics.events.store'), [
            'event_type' => 'page_view',
            'source' => 'web',
            'subject' => 'insights',
            'subject_label' => 'Insights',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.event_type', 'page_view')
            ->assertJsonPath('data.subject', 'insights');

        $this->assertDatabaseHas('analytics_events', [
            'event_type' => 'page_view',
            'subject' => 'insights',
        ]);
    }

    public function test_analytics_event_api_rejects_invalid_event_type(): void
    {
        $before = AnalyticsEvent::query()->count();

        $this->postJson(route('api.analytics.events.store'), [
            'event_type' => 'invalid_event',
            'subject' => 'home',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['event_type']);

        $this->assertSame($before, AnalyticsEvent::query()->count());
    }
}
