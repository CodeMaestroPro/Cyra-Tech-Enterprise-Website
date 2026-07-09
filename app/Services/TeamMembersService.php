<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Repositories\TeamMemberRepository;

class TeamMembersService extends BaseService
{
    public function __construct(
        private readonly TeamMemberRepository $teamMemberRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.team_members_workspace', []);
        $departmentMeta = $configured['department_meta'] ?? [];
        $members = $this->teamMemberRepository->getAllMembers();

        $mappedMembers = $members
            ->map(fn (TeamMember $member) => $this->formatMember($member, $departmentMeta))
            ->values()
            ->all();

        $activeCount = $members->where('is_active', true)->count();

        return [
            'summary' => [
                'total_members' => $members->count(),
                'active_members' => $activeCount,
                'inactive_members' => $members->count() - $activeCount,
                'featured_members' => $members->where('is_featured', true)->where('is_active', true)->count(),
                'departments' => $members->pluck('department')->unique()->count(),
            ],
            'description' => $configured['summary'] ?? 'Manage the Cyra-Tech team directory.',
            'members' => $mappedMembers,
            'featured_members' => collect($mappedMembers)->where('is_featured', true)->where('is_active', true)->values()->all(),
            'departments' => $this->buildDepartmentBreakdown($members, $departmentMeta),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, array<string, string>>  $departmentMeta
     * @return array<string, mixed>
     */
    private function formatMember(TeamMember $member, array $departmentMeta): array
    {
        $meta = $departmentMeta[$member->department] ?? [];
        $nameParts = preg_split('/\s+/', trim($member->name)) ?: [];
        $initials = collect($nameParts)
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        return [
            'id' => $member->id,
            'slug' => $member->slug,
            'name' => $member->name,
            'title' => $member->title,
            'department' => $member->department,
            'department_label' => $meta['label'] ?? ucfirst($member->department),
            'department_icon' => $meta['icon'] ?? 'users',
            'location' => $member->location,
            'work_type' => $member->work_type,
            'bio' => $member->bio,
            'skills' => $member->skills ?? [],
            'linkedin_url' => $member->linkedin_url,
            'email' => $member->email,
            'initials' => $initials,
            'sort_order' => $member->sort_order,
            'is_active' => $member->is_active,
            'is_featured' => $member->is_featured,
            'updated_at' => $member->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, TeamMember>  $members
     * @param  array<string, array<string, string>>  $departmentMeta
     * @return list<array<string, mixed>>
     */
    private function buildDepartmentBreakdown($members, array $departmentMeta): array
    {
        return $members
            ->groupBy('department')
            ->map(function ($group, string $department) use ($departmentMeta) {
                $meta = $departmentMeta[$department] ?? [];

                return [
                    'department' => $department,
                    'label' => $meta['label'] ?? ucfirst($department),
                    'icon' => $meta['icon'] ?? 'users',
                    'count' => $group->count(),
                    'active_count' => $group->where('is_active', true)->count(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $links
     * @return list<array<string, mixed>>
     */
    private function buildQuickLinks(array $links): array
    {
        return collect($links)
            ->map(function (array $link) {
                $route = $link['route'] ?? null;

                return [
                    'label' => $link['label'] ?? '',
                    'icon' => $link['icon'] ?? 'link',
                    'description' => $link['description'] ?? '',
                    'href' => $route ? route($route) : ($link['url'] ?? '#'),
                    'external' => $link['external'] ?? false,
                ];
            })
            ->values()
            ->all();
    }
}
