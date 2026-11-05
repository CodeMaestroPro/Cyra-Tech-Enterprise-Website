<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('free_training_registrations', function (Blueprint $table) {
            $table->string('phone_digits', 20)->nullable()->after('phone');
        });

        $registrations = DB::table('free_training_registrations')->orderBy('id')->get();

        foreach ($registrations as $registration) {
            DB::table('free_training_registrations')
                ->where('id', $registration->id)
                ->update([
                    'email' => Str::lower(trim((string) $registration->email)),
                    'phone_digits' => $this->normalizePhoneDigits((string) $registration->phone) ?: null,
                ]);
        }

        $seenEmails = [];
        $seenPhones = [];
        $duplicateIds = [];

        foreach (DB::table('free_training_registrations')->orderBy('id')->get() as $registration) {
            $email = Str::lower((string) $registration->email);
            $digits = (string) ($registration->phone_digits ?: '');
            $isDuplicate = isset($seenEmails[$email])
                || ($digits !== '' && isset($seenPhones[$digits]));

            if ($isDuplicate) {
                $duplicateIds[] = $registration->id;
                continue;
            }

            $seenEmails[$email] = true;

            if ($digits !== '') {
                $seenPhones[$digits] = true;
            }
        }

        if ($duplicateIds !== []) {
            DB::table('free_training_registrations')->whereIn('id', $duplicateIds)->delete();
        }

        Schema::table('free_training_registrations', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->unique('email');
            $table->unique('phone_digits');
        });
    }

    public function down(): void
    {
        Schema::table('free_training_registrations', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropUnique(['phone_digits']);
            $table->index('email');
            $table->dropColumn('phone_digits');
        });
    }

    private function normalizePhoneDigits(string $phone): string
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
};
