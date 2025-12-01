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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('adiutor_id')->nullable()->constrained('users')->onDelete('set null'); // Legacy: not used for project feedback, adiutor ratings are derived
            $table->unsignedBigInteger('task_id')->nullable();
            $table->foreign('task_id')->references('taskID')->on('tasks')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            
            // Feedback Content
            $table->string('title')->nullable();
            $table->text('message');
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->enum('type', ['general', 'service', 'technical', 'complaint', 'suggestion'])->default('general');
            
            // Status and Processing
            $table->enum('status', ['pending', 'reviewed', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Admin Response
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();
            
            // Resolution
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            
            // Internal Notes
            $table->text('internal_notes')->nullable();
            
            // Categories/Tags
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('client_id');
            $table->index('adiutor_id');
            $table->index('status');
            $table->index('rating');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
