<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    protected $fillable = [
        'reference',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'state',
        'city',
        'education_level',
        'field_of_study',
        'institution',
        'course_of_study',
        'academic_level',
        'program',
        'skill_level',
        'has_tech_experience',
        'amount',
        'payment_plan',
        'amount_paid',
        'amount_remaining',
        'currency',
        'motivation',
        'status',
        'payment_status',
        'paystack_reference',
        'balance_paystack_reference',
        'paid_at',
        'ip_address',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'has_tech_experience' => 'boolean',
            'paid_at' => 'datetime',
        ];
    }
}
