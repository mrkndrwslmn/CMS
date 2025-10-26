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
        Schema::create('revision_requests', function (Blueprint $table) {
            $table->id();
            
            // Document reference - references documentID column
            $table->foreignId('document_id')->constrained('documents', 'documentID')->onDelete('cascade');
            
            // Who requested the revision (client)
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            
            // Revision details
            $table->text('reason'); // Why the revision is needed
            $table->date('requested_due_date')->nullable(); // When client needs it revised by
            $table->integer('revision_number')->default(1); // Track multiple revisions
            
            // Status tracking
            $table->enum('status', [
                'pending',      // Waiting for admin review
                'approved',     // Admin approved, adiutor should work on it
                'rejected',     // Admin rejected the request
                'completed',    // Adiutor completed the revision
                'cancelled'     // Cancelled by client or admin
            ])->default('pending');
            
            // Admin review
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_notes')->nullable(); // Admin's notes/reason for approval/rejection
            $table->timestamp('reviewed_at')->nullable();
            
            // Task reference (if document is task-based) - tasks use taskID as primary key
            $table->foreignId('task_id')->nullable()->constrained('tasks', 'taskID')->onDelete('set null');
            
            // Service request / Project reference
            $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            
            // Source type indicator
            $table->enum('source_type', ['task', 'project']); // Where the document came from
            
            // Adiutor who will handle the revision
            $table->foreignId('assigned_adiutor_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Completion tracking
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('status');
            $table->index('source_type');
            $table->index(['document_id', 'status']);
            $table->index(['assigned_adiutor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_requests');
    }
};
