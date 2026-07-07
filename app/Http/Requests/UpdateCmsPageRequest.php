<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCmsPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('cms.update') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $templates = collect(config('cyra.cms.templates', []))->pluck('slug')->all();

        return [
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['prohibited'],
            'template' => ['required', 'string', Rule::in($templates)],
            'status' => ['required', 'string', Rule::in(config('cyra.cms.statuses', ['draft', 'published']))],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:20000'],
            'seo_title' => ['nullable', 'string', 'max:160'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
