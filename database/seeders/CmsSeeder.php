<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()
            ->where('email', config('cyra.admin.email'))
            ->first();

        $sort = 1;

        foreach (config('cyra.cms.pages', []) as $page) {
            CmsPage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'template' => $page['template'] ?? 'legal',
                    'status' => $page['status'] ?? 'published',
                    'eyebrow' => $page['eyebrow'] ?? null,
                    'excerpt' => $page['excerpt'] ?? null,
                    'description' => $page['description'] ?? null,
                    'content' => $page['content'] ?? [],
                    'seo' => $page['seo'] ?? [],
                    'author_id' => $author?->id,
                    'published_at' => ($page['status'] ?? 'published') === 'published' ? now() : null,
                    'sort_order' => $sort++,
                    'is_active' => true,
                ],
            );
        }
    }
}
