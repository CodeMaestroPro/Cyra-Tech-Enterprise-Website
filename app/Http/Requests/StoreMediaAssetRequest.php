<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('media.upload') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $categories = collect(config('cyra.media_library.categories', []))->pluck('slug')->all();
        $maxKb = (int) config('cyra.media_library.max_upload_kb', 5120);
        $mimes = collect(config('cyra.media_library.allowed_mime_types', []))->keys()->all();

        return [
            'file' => ['required', 'file', 'max:'.$maxKb, 'mimetypes:'.implode(',', $mimes)],
            'title' => ['nullable', 'string', 'max:160'],
            'category' => ['required', 'string', Rule::in($categories)],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
