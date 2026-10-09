<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRegistrationRequest extends FormRequest
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
        $educationLevels = collect(config('cyra.student_registrations.education_levels', []))->pluck('slug')->all();
        $genders = collect(config('cyra.student_registrations.genders', []))->pluck('slug')->all();
        $states = config('cyra.student_registrations.states', []);
        $skillLevels = collect(config('cyra.student_registrations.skill_levels', []))->pluck('slug')->all();
        $programs = collect(config('cyra.student_registrations.programs', []))->pluck('slug')->all();
        $paymentPlans = array_keys(config('cyra.student_registrations.payment.plans', [
            'full' => [],
            'installment' => [],
        ]));

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', Rule::in($genders)],
            'state' => ['required', 'string', Rule::in($states)],
            'city' => ['required', 'string', 'max:80'],
            'education_level' => ['required', 'string', Rule::in($educationLevels)],
            'field_of_study' => ['required', 'string', 'max:160'],
            'program' => ['required', 'string', Rule::in($programs)],
            'skill_level' => ['required', 'string', Rule::in($skillLevels)],
            'has_tech_experience' => ['required', 'in:yes,no'],
            'payment_plan' => ['required', 'string', Rule::in($paymentPlans)],
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
            'education_level.required' => 'Please select your highest level of education.',
            'education_level.in' => 'Please select a valid education level.',
            'field_of_study.required' => 'Please enter your field of study.',
            'program.required' => 'Please select a training program.',
            'program.in' => 'Please select a valid training program.',
            'skill_level.required' => 'Please select your current tech skill level.',
            'skill_level.in' => 'Please select a valid skill level.',
            'has_tech_experience.required' => 'Please tell us if you have previous technology experience.',
            'payment_plan.required' => 'Please choose how you would like to pay.',
            'payment_plan.in' => 'Please select a valid payment option.',
        ];
    }
}
