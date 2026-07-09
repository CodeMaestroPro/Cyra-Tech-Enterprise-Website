<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePartnerProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('partners.update') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $categories = collect(config('cyra.partner_hub.categories', []))
            ->pluck('slug')
            ->reject(fn (string $slug) => $slug === 'all')
            ->values()
            ->all();

        return [
            'category' => ['required', 'string', Rule::in($categories)],
            'title' => ['required', 'string', 'max:160'],
            'partner_type' => ['required', 'string', 'max:120'],
            'region' => ['required', 'string', 'max:120'],
            'engagement_model' => ['required', 'string', 'max:120'],
            'tagline' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:5000'],
            'benefits' => ['nullable', 'string', 'max:5000'],
            'requirements' => ['nullable', 'string', 'max:5000'],
            'enablement' => ['nullable', 'string', 'max:5000'],
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

        $validated['is_active'] = $this->boolean('is_active');
        $validated['is_featured'] = $this->boolean('is_featured');

        return $validated;
    }
}
