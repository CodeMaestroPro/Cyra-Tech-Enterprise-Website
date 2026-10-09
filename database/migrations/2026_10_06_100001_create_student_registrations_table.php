<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('institution');
            $table->string('course_of_study');
            $table->string('academic_level');
            $table->string('program');
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('NGN');
            $table->text('motivation');
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('paystack_reference')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['payment_status', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
