<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('projects.update') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'client_name' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'string', Rule::in(collect(config('cyra.project_management.statuses', []))->pluck('slug')->all())],
            'phase' => ['required', 'string', Rule::in(collect(config('cyra.project_management.phases', []))->pluck('slug')->all())],
            'priority' => ['required', 'string', Rule::in(collect(config('cyra.project_management.priorities', []))->pluck('slug')->all())],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'project_manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'client_engagement_id' => ['nullable', 'integer', 'exists:client_engagements,id'],
            'crm_lead_id' => ['nullable', 'integer', 'exists:crm_leads,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
