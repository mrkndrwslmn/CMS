<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * MILESTONE MANAGEMENT SYSTEM
     * Implements support for three payment types:
     * 1. Full Payment - All content accessible immediately
     * 2. Milestone Payment - Content locked per phase until paid
     * 3. Downpayment - Content locked until remaining balance paid
     */
    public function up(): void
    {
        // Project Milestones (Phases) Table
        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            // Phase details
            $table->string('phase_name'); // e.g., "Phase 1: Design", "Phase 2: Development"
            $table->text('phase_description')->nullable();
            $table->integer('phase_order')->default(1); // Sequence: 1, 2, 3...
            
            // Financial details
            $table->decimal('percentage', 5, 2); // 0.00 to 100.00
            $table->decimal('amount', 10, 2); // Calculated: project_budget * percentage
            
            // Phase timeline
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            
            // Status tracking
            $table->enum('status', [
                'pending',      // Not started
                'in_progress',  // Currently active
                'completed',    // Phase work completed
                'paid'          // Payment received for this phase
            ])->default('pending');
            
            // Payment status
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            
            // Additional info
            $table->text('notes')->nullable();
            $table->json('deliverables')->nullable(); // List of expected deliverables
            
            $table->timestamps();
            
            // Indexes
            $table->index(['project_id', 'phase_order']);
            $table->index(['project_id', 'is_paid']);
            $table->index('status');
        });

        // Milestone Payments Table - Tracks payments for each phase
        Schema::create('milestone_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('milestone_id')->constrained('project_milestones')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            // Payment details
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->enum('status', [
                'pending',      // Awaiting payment
                'partial',      // Partially paid
                'paid',         // Fully paid
                'overdue',      // Past due date
                'cancelled'     // Cancelled
            ])->default('pending');
            
            // Dates
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Tracking
            $table->text('notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users'); // Admin who confirmed
            
            $table->timestamps();
            
            // Indexes
            $table->index(['milestone_id', 'status']);
            $table->index(['service_request_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone_payments');
        Schema::dropIfExists('project_milestones');
    }
};
