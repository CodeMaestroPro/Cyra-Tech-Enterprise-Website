<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = [
        'reference',
        'name',
        'email',
        'company',
        'phone',
        'inquiry_type',
        'message',
        'status',
        'ip_address',
    ];
}
