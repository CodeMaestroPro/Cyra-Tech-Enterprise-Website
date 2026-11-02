<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeTrainingRegistration extends Model
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
        'program',
        'skill_level',
        'has_tech_experience',
        'status',
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
        ];
    }
}
