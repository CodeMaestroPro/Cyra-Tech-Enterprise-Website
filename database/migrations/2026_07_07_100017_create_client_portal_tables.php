<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('industry');
            $table->string('region');
            $table->string('account_manager');
            $table->string('support_email');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('client_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_account_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('portfolio_slug')->nullable();
            $table->string('title');
            $table->string('status');
            $table->string('phase');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('tagline');
            $table->string('summary');
            $table->text('description');
            $table->json('milestones')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('team')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['client_account_id', 'slug']);
            $table->index(['is_active', 'sort_order']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('client_account_id')->nullable()->after('is_active')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_account_id');
        });

        Schema::dropIfExists('client_engagements');
        Schema::dropIfExists('client_accounts');
    }
};
