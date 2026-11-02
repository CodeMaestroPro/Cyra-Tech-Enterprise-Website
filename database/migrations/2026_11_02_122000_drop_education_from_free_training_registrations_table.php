<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('free_training_registrations', function (Blueprint $table) {
            $table->dropColumn(['education_level', 'field_of_study']);
        });
    }

    public function down(): void
    {
        Schema::table('free_training_registrations', function (Blueprint $table) {
            $table->string('education_level')->nullable();
            $table->string('field_of_study')->nullable();
        });
    }
};
