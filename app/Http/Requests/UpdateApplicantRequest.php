<?php

namespace App\Http\Requests;

use App\Services\ApplicantsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('crm.update') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(app(ApplicantsService::class)->getStatusOptions())],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
