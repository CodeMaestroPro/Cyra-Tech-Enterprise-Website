<?php

namespace Tests\Feature;

use App\Models\NavigationItem;
use App\Models\User;
use App\Services\NavigationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_solutions_sidebar_links_resolve_to_admin_catalog_routes(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        NavigationItem::query()
            ->where('location', 'admin_sidebar')
            ->where('group_label', 'Solutions')
            ->update(['route_name' => 'solutions']);

        $navigation = app(NavigationService::class)->getAdminNavigation($admin);
        $solutions = collect($navigation['groups'])->firstWhere('label', 'Solutions');
        $links = collect($solutions['items'] ?? [])->mapWithKeys(
            fn (array $item): array => [$item['label'] => $item['url']],
        );

        $this->assertSame(route('admin.solutions.index'), $links['Services']);
        $this->assertSame(route('admin.industries.index'), $links['Industries']);
        $this->assertSame(route('admin.products.index'), $links['Products']);
        $this->assertSame(route('admin.innovation-lab.index'), $links['Innovation Lab']);
        $this->assertSame(route('admin.portfolio.index'), $links['Portfolio']);
        $this->assertSame(route('admin.portfolio.index'), $links['Case Studies']);

        $this->actingAs($admin)->get(route('admin.solutions.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.industries.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.products.index'))->assertOk();
    }

    public function test_people_sidebar_links_resolve_to_admin_catalog_routes(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        NavigationItem::query()
            ->where('location', 'admin_sidebar')
            ->where('group_label', 'People')
            ->whereIn('label', ['Leadership', 'Careers', 'Community'])
            ->update(['route_name' => 'leadership']);

        $navigation = app(NavigationService::class)->getAdminNavigation($admin);
        $people = collect($navigation['groups'])->firstWhere('label', 'People');
        $links = collect($people['items'] ?? [])->mapWithKeys(
            fn (array $item): array => [$item['label'] => $item['url']],
        );

        $this->assertSame(route('admin.leadership.index'), $links['Leadership']);
        $this->assertSame(route('admin.team-members.index'), $links['Team Members']);
        $this->assertSame(route('admin.careers.index'), $links['Careers']);
        $this->assertSame(route('admin.community.index'), $links['Community']);

        $this->actingAs($admin)->get(route('admin.leadership.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.careers.index'))->assertOk();
    }

    public function test_solutions_catalog_supports_crud(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.solutions.store'), [
                'title' => 'Test Service Offering',
                'slug' => 'test-service-offering',
                'category' => 'transformation',
                'summary' => 'A test summary for catalog CRUD.',
                'description' => 'A detailed test description for the service offering.',
                'capabilities' => "Capability one\nCapability two",
                'outcomes' => "Outcome one",
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.solutions.edit', 'test-service-offering'));

        $this->actingAs($admin)
            ->put(route('admin.solutions.update', 'test-service-offering'), [
                'title' => 'Updated Service Offering',
                'category' => 'platform',
                'summary' => 'Updated summary for catalog CRUD.',
                'description' => 'Updated detailed description.',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.solutions.edit', 'test-service-offering'));

        $this->assertDatabaseHas('solution_offerings', [
            'slug' => 'test-service-offering',
            'title' => 'Updated Service Offering',
            'category' => 'platform',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.solutions.destroy', 'test-service-offering'))
            ->assertRedirect(route('admin.solutions.index'));

        $this->assertDatabaseMissing('solution_offerings', [
            'slug' => 'test-service-offering',
        ]);
    }
}
