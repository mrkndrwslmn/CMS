<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // web-development, mobile-app, design, etc.
            $table->json('default_tasks')->nullable(); // Template tasks structure
            $table->json('skills_required')->nullable(); // Required skills for this template
            $table->json('milestones_template')->nullable(); // Default milestone structure
            $table->decimal('estimated_budget_min', 10, 2)->nullable();
            $table->decimal('estimated_budget_max', 10, 2)->nullable();
            $table->integer('estimated_duration_days')->nullable();
            $table->enum('budget_type', ['fixed', 'hourly'])->default('fixed');
            $table->enum('payment_type', ['full_payment', 'milestone_payment', 'downpayment'])->default('milestone_payment');
            $table->text('requirements_template')->nullable(); // Default requirements text
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_templates');
    }
};
