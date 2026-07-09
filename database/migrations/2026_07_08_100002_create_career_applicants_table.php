<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('opening_slug');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_filename')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->default('website');
            $table->text('notes')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'applied_at']);
            $table->index('opening_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_applicants');
    }
};
