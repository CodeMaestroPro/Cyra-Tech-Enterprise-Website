<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_training_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('education_level')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('program');
            $table->string('skill_level')->nullable();
            $table->boolean('has_tech_experience')->default(false);
            $table->string('status')->default('registered');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
            $table->index('program');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_training_registrations');
    }
};
