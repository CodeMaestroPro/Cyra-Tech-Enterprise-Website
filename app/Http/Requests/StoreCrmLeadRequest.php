<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCrmLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('crm.create') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'company' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'source' => ['required', 'string', Rule::in(collect(config('cyra.crm.sources', []))->pluck('slug')->all())],
            'pipeline_stage' => ['nullable', 'string', Rule::in(collect(config('cyra.crm.pipeline_stages', []))->pluck('slug')->all())],
            'priority' => ['nullable', 'string', Rule::in(collect(config('cyra.crm.priorities', []))->pluck('slug')->all())],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'contact_inquiry_id' => ['nullable', 'integer', 'exists:contact_inquiries,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
