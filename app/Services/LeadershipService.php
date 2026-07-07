<?php

namespace App\Services;

use App\Models\LeadershipProfile;
use App\Repositories\LeadershipProfileRepository;

class LeadershipService extends BaseService
{
    public function __construct(
        private readonly LeadershipProfileRepository $leadershipProfileRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getLeadership(): array
    {
        $profiles = $this->leadershipProfileRepository
            ->getActiveProfiles()
            ->map(fn (LeadershipProfile $profile) => $this->formatProfile($profile))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.leadership.hero', []),
            'governance' => config('cyra.leadership.governance', []),
            'cta' => config('cyra.leadership.cta', []),
            'executives' => collect($profiles)->where('tier', 'executive')->values()->all(),
            'profiles' => $profiles,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProfile(string $slug): ?array
    {
        $profile = $this->leadershipProfileRepository->findActiveBySlug($slug);

        if ($profile === null) {
            return null;
        }

        return $this->formatProfile($profile);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.leadership.seo', []);

        return [
            'title' => $seo['title'] ?? 'Leadership | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Meet the Cyra-Tech executive team guiding enterprise innovation and client success.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProfile(LeadershipProfile $profile): array
    {
        $nameParts = preg_split('/\s+/', trim($profile->name)) ?: [];
        $initials = collect($nameParts)
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        return [
            'slug' => $profile->slug,
            'name' => $profile->name,
            'title' => $profile->title,
            'tier' => $profile->tier,
            'bio' => $profile->bio,
            'focus_areas' => $profile->focus_areas ?? [],
            'linkedin_url' => $profile->linkedin_url,
            'email' => $profile->email,
            'initials' => $initials,
            'is_featured' => $profile->is_featured,
        ];
    }
}
