<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_openings', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('title');
            $table->string('department');
            $table->string('location');
            $table->string('work_type');
            $table->string('tagline');
            $table->string('summary');
            $table->text('description');
            $table->json('responsibilities')->nullable();
            $table->json('requirements')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('badge')->nullable();
            $table->string('icon')->default('spark');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'category', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_openings');
    }
};
