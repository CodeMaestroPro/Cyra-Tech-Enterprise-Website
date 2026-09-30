<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItStudentRegistrationRequest extends FormRequest
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
        $levels = collect(config('cyra.it_students.academic_levels', []))->pluck('slug')->all();
        $interests = collect(config('cyra.it_students.interest_areas', []))->pluck('slug')->all();
        $availability = collect(config('cyra.it_students.availability_options', []))->pluck('slug')->all();

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'institution' => ['required', 'string', 'max:160'],
            'course_of_study' => ['required', 'string', 'max:160'],
            'academic_level' => ['required', 'string', Rule::in($levels)],
            'interest_area' => ['required', 'string', Rule::in($interests)],
            'availability' => ['required', 'string', Rule::in($availability)],
            'motivation' => ['required', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'institution.required' => 'Please enter your school or institution.',
            'course_of_study.required' => 'Please enter your course of study.',
            'academic_level.required' => 'Please select your academic level.',
            'academic_level.in' => 'Please select a valid academic level.',
            'interest_area.required' => 'Please select your area of interest.',
            'interest_area.in' => 'Please select a valid area of interest.',
            'availability.required' => 'Please select your availability.',
            'availability.in' => 'Please select a valid availability option.',
            'motivation.required' => 'Please tell us why you want to join Cyra-Tech.',
        ];
    }
}
