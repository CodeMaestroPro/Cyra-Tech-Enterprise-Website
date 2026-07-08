<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnalyticsEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $eventTypes = collect(config('cyra.analytics.event_types', []))->pluck('slug')->all();
        $sources = collect(config('cyra.analytics.sources', []))->pluck('slug')->all();

        return [
            'event_type' => ['required', 'string', Rule::in($eventTypes)],
            'source' => ['nullable', 'string', Rule::in($sources)],
            'subject' => ['required', 'string', 'max:160'],
            'subject_label' => ['nullable', 'string', 'max:160'],
            'metadata' => ['nullable', 'array'],
            'value' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
