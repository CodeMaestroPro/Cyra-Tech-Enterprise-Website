<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RunOptimizationActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('optimization.manage') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'action' => [
                'required',
                'string',
                Rule::in(collect(config('cyra.testing_optimization.actions', []))->pluck('slug')->all()),
            ],
        ];
    }
}
