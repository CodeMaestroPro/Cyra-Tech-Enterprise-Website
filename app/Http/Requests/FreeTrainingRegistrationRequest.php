<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FreeTrainingRegistrationRequest extends FormRequest
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
        $genders = collect(config('cyra.student_registrations.genders', []))->pluck('slug')->all();
        $states = config('cyra.student_registrations.states', []);
        $skillLevels = collect(config('cyra.student_registrations.skill_levels', []))->pluck('slug')->all();
        $programs = collect(config('cyra.free_training.programs', []))->pluck('slug')->all();

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', Rule::in($genders)],
            'state' => ['required', 'string', Rule::in($states)],
            'city' => ['required', 'string', 'max:80'],
            'program' => ['required', 'string', Rule::in($programs)],
            'skill_level' => ['required', 'string', Rule::in($skillLevels)],
            'has_tech_experience' => ['required', 'in:yes,no'],
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
            'date_of_birth.required' => 'Please enter your date of birth.',
            'date_of_birth.before' => 'Please enter a valid date of birth.',
            'gender.required' => 'Please select your gender.',
            'state.required' => 'Please select your state.',
            'city.required' => 'Please enter your city.',
            'program.required' => 'Please select a free training course.',
            'program.in' => 'Please select a valid free training course.',
            'skill_level.required' => 'Please select your current tech skill level.',
            'skill_level.in' => 'Please select a valid skill level.',
            'has_tech_experience.required' => 'Please tell us if you have previous technology experience.',
        ];
    }
}
