<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPREHENSIVE PROJECT SYSTEM
     * Consolidates: 2025_10_09_131246_create_projects_table.php + 2025_10_09_131258_create_project_assignments_table.php + 2025_10_14_100000_add_service_request_id_to_projects_table.php
     */
    public function up(): void
    {
        // Projects table with service request relationship and all enhancements
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // CORRECT WORKFLOW: Projects are created FROM service requests when approved + paid
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            // Project details
            $table->string('title');
            $table->text('description');
            $table->json('requirements')->nullable(); // Technical requirements
            $table->json('skills_required')->nullable(); // Required skill IDs
            
            // Budget and timeline
            $table->decimal('budget', 10, 2)->nullable();
            $table->enum('budget_type', ['fixed', 'hourly'])->default('fixed');
            $table->date('deadline')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Status and priority
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['active', 'in_progress', 'review', 'completed', 'cancelled'])->default('active');
            
            // Attachments
            $table->json('attachments')->nullable(); // File paths
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'priority']);
            $table->index(['client_id', 'status']);
            $table->index('deadline');
            $table->unique('service_request_id'); // One project per service request
        });

        // Project assignments table (adiutors assigned to projects)
        Schema::create('project_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('agreed_rate', 8, 2)->nullable(); // Hourly or fixed rate
            $table->date('start_date')->nullable();
            $table->date('expected_completion')->nullable();
            $table->enum('status', ['assigned', 'active', 'completed', 'removed'])->default('assigned');
            $table->text('notes')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
            
            $table->unique(['project_id', 'adiutor_id']);
            $table->index(['adiutor_id', 'status']);
        });

        // Project feedback table
        Schema::create('project_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('adiutor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('feedback')->nullable();
            $table->enum('feedback_type', ['project', 'adiutor', 'overall'])->default('project');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            
            $table->index(['project_id', 'feedback_type']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_feedback');
        Schema::dropIfExists('project_assignments');
        Schema::dropIfExists('projects');
    }
};