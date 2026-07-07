<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('template')->default('standard');
            $table->string('status')->default('draft');
            $table->string('eyebrow')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->json('seo')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['status', 'is_active', 'sort_order']);
            $table->index('template');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
