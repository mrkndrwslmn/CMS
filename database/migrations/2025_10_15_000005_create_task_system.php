<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPREHENSIVE TASK SYSTEM
     * Consolidates: 2025_10_10_011808_create_tasks_table.php + 2025_10_35341_add_client_id_to_tasks_table.php + 2025_10_14_061347_add_budget_allocation_to_tasks_table.php + 2025_10_14_100001_fix_tasks_project_relationship.php
     */
    public function up(): void
    {
        // Tasks table with CORRECT project relationship and all enhancements
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('taskID');
            
            // CORRECT RELATIONSHIP: Tasks belong to PROJECTS, not service requests directly
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
<<<<<<< HEAD
            // Phase reference for milestone payment projects (nullable - will be constrained in later migration)
            $table->unsignedBigInteger('phase_id')->nullable();
            
=======
>>>>>>> 7c71488 (Initial commit from Princess)
            // Task assignment
            $table->foreignId('assignedTo')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('createdBy')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            // Task details
            $table->string('taskTitle');
            $table->text('taskDescription');
<<<<<<< HEAD
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'pending_approval'])->default('pending');
=======
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
>>>>>>> 7c71488 (Initial commit from Princess)
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Timeline
            $table->timestamp('deadline')->nullable();
            $table->timestamp('dateAssigned')->default(now());
            $table->timestamp('completedAt')->nullable();
            
            // Budget allocation
            $table->decimal('allocated_budget', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            
            // Progress tracking
            $table->integer('progress_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->text('completion_notes')->nullable();
            $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->onDelete('set null'); // DEPRECATED
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'priority', 'deadline']);
            $table->index(['assignedTo', 'status']);
            $table->index(['project_id', 'status']);
            $table->index(['client_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};