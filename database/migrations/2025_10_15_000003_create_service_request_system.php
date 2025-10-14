<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPREHENSIVE SERVICE REQUEST SYSTEM
     * Consolidates: 2025_10_09_133059_create_service_requests_table.php + 2025_10_14_061311_enhance_service_requests_for_refined_workflow.php + 2025_10_09_133207_create_request_attachments_table.php
     */
    public function up(): void
    {
        // Service requests table with all workflow enhancements
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            // Contact information
            $table->string('contact_method'); // email, messenger, phone
            $table->string('contact_details');
            
            // Request details
            $table->string('service_type');
            $table->string('project_name');
            $table->text('request_description');
            $table->date('deadline')->nullable();
            $table->text('expectations')->nullable();
            $table->text('additional_notes')->nullable();
            $table->json('requirements')->nullable(); // Structured requirements
            
            // Status and priority
            $table->enum('status', [
                'pending', 
                'approved', 
                'rejected', 
                'pending_payment', 
                'paid', 
                'in_progress', 
                'completed'
            ])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Budget information
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->decimal('approved_budget', 10, 2)->nullable();
            
            // Approval workflow
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            
            // Payment workflow
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_due_date')->nullable();
            $table->timestamp('payment_confirmed_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_instructions')->nullable();
            
            // Admin notes
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['status', 'priority']);
            $table->index(['client_id', 'status']);
            $table->index('deadline');
        });

        // Request attachments table
        Schema::create('request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes
            $table->string('file_hash')->nullable(); // For duplicate detection
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('mime_type');
            $table->index('file_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_attachments');
        Schema::dropIfExists('service_requests');
    }
};