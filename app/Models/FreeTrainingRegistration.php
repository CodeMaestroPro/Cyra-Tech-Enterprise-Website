<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FreeTrainingRegistration extends Model
{
    protected $fillable = [
        'reference',
        'name',
        'email',
        'phone',
        'phone_digits',
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

    public static function normalizeEmail(string $email): string
    {
        return Str::lower(trim($email));
    }

    public static function normalizePhoneDigits(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '234') && strlen($digits) >= 13) {
            return substr($digits, 0, 13);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return '234'.substr($digits, 1);
        }

        return $digits;
    }

    /**
     * @return Attribute<string, string>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => self::normalizeEmail($value),
        );
    }

    /**
     * @return Attribute<string, string>
     */
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: function (string $value): array {
                return [
                    'phone' => $value,
                    'phone_digits' => self::normalizePhoneDigits($value) ?: null,
                ];
            },
        );
    }
}
