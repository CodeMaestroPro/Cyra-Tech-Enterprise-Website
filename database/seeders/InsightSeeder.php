<?php

namespace Database\Seeders;

use App\Models\InsightArticle;
use Illuminate\Database\Seeder;

class InsightSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (config('cyra.insights.articles', []) as $article) {
            InsightArticle::query()->updateOrCreate(
                ['slug' => $article['slug']],
                [
                    'category' => $article['category'],
                    'title' => $article['title'],
                    'tagline' => $article['tagline'],
                    'summary' => $article['summary'],
                    'description' => $article['description'],
                    'author' => $article['author'],
                    'read_time' => $article['read_time'],
                    'topics' => $article['topics'] ?? [],
                    'takeaways' => $article['takeaways'] ?? [],
                    'published_label' => $article['published_label'] ?? null,
                    'badge' => $article['badge'] ?? null,
                    'icon' => $article['icon'] ?? 'spark',
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_featured' => $article['is_featured'] ?? true,
                ],
            );
        }
    }
}
