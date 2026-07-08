<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->string('source')->default('website');
            $table->string('pipeline_stage')->default('new');
            $table->string('priority')->default('medium');
            $table->decimal('estimated_value', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('contact_inquiry_id')->nullable()->constrained('contact_inquiries')->nullOnDelete();
            $table->timestamp('last_contacted_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['pipeline_stage', 'is_active', 'sort_order']);
            $table->index('source');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};
