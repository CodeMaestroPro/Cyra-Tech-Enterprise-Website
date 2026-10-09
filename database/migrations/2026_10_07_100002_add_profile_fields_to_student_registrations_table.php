<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('state')->nullable()->after('gender');
            $table->string('city')->nullable()->after('state');
            $table->string('education_level')->nullable()->after('city');
            $table->string('field_of_study')->nullable()->after('education_level');
            $table->string('skill_level')->nullable()->after('program');
            $table->boolean('has_tech_experience')->nullable()->after('skill_level');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'state',
                'city',
                'education_level',
                'field_of_study',
                'skill_level',
                'has_tech_experience',
            ]);
        });
    }
};
