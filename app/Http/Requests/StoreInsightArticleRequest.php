<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInsightArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('cms.create') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $categories = collect(config('cyra.insights.categories', []))
            ->pluck('slug')
            ->reject(fn (string $slug) => $slug === 'all')
            ->values()
            ->all();

        return [
            'slug' => ['required', 'string', 'max:160', 'alpha_dash', 'unique:insight_articles,slug'],
            'category' => ['required', 'string', Rule::in($categories)],
            'title' => ['required', 'string', 'max:160'],
            'tagline' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:10000'],
            'author' => ['required', 'string', 'max:160'],
            'read_time' => ['required', 'string', 'max:40'],
            'topics' => ['nullable', 'string', 'max:5000'],
            'takeaways' => ['nullable', 'string', 'max:5000'],
            'published_label' => ['nullable', 'string', 'max:60'],
            'badge' => ['nullable', 'string', 'max:60'],
            'icon' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        $validated['is_active'] = $this->boolean('is_active', true);
        $validated['is_featured'] = $this->boolean('is_featured', false);

        return $validated;
    }
}
