<?php

namespace Tests\Feature;

use App\Models\NavigationItem;
use App\Models\User;
use App\Services\NavigationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGrowthNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_growth_sidebar_links_stay_in_admin_area_even_with_stale_database_routes(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        NavigationItem::query()
            ->where('location', 'admin_sidebar')
            ->where('group_label', 'Growth')
            ->whereIn('label', ['Partners', 'Contact', 'Insights'])
            ->update(['route_name' => 'partner-hub']);

        $navigation = app(NavigationService::class)->getAdminNavigation($admin);
        $growth = collect($navigation['groups'])->firstWhere('label', 'Growth');
        $links = collect($growth['items'] ?? [])->mapWithKeys(
            fn (array $item): array => [$item['label'] => $item['url']],
        );

        $this->assertSame(route('admin.contact.index'), $links['Contact']);
        $this->assertSame(route('admin.crm.index'), $links['Leads & CRM']);
        $this->assertSame(route('admin.partners.index'), $links['Partners']);
        $this->assertSame(route('admin.marketing.index'), $links['Marketing']);
        $this->assertSame(route('admin.insights.index'), $links['Insights']);
        $this->assertSame(route('admin.analytics.index'), $links['Analytics']);

        $this->actingAs($admin)->get(route('admin.partners.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.contact.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.insights.index'))->assertOk();
    }
}
