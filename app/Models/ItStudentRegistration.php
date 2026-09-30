<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItStudentRegistration extends Model
{
    protected $fillable = [
        'reference',
        'name',
        'email',
        'phone',
        'institution',
        'course_of_study',
        'academic_level',
        'interest_area',
        'availability',
        'motivation',
        'status',
        'ip_address',
    ];
}
