<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('payment_plan')->default('full')->after('amount');
            $table->unsignedInteger('amount_paid')->default(0)->after('payment_plan');
            $table->unsignedInteger('amount_remaining')->default(0)->after('amount_paid');
            $table->string('balance_paystack_reference')->nullable()->unique()->after('paystack_reference');
        });
    }

    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_plan',
                'amount_paid',
                'amount_remaining',
                'balance_paystack_reference',
            ]);
        });
    }
};
