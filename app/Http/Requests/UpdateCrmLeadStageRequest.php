<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCrmLeadStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('crm.manage') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'pipeline_stage' => ['required', 'string', Rule::in(collect(config('cyra.crm.pipeline_stages', []))->pluck('slug')->all())],
        ];
    }
}
