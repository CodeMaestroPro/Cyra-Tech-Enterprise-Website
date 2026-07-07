<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactInquiryRequest extends FormRequest
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
        $inquirySlugs = collect(config('cyra.contact.inquiry_types', []))
            ->pluck('slug')
            ->all();

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'inquiry_type' => ['required', 'string', Rule::in($inquirySlugs)],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'inquiry_type.required' => 'Please select an inquiry type.',
            'inquiry_type.in' => 'Please select a valid inquiry type.',
            'message.required' => 'Please enter your message.',
        ];
    }
}
