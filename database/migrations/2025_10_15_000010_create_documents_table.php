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
        Schema::create('documents', function (Blueprint $table) {
            $table->id('documentID');
            
            // Polymorphic relationship - documents can belong to various entities
            $table->string('documentable_type')->nullable(); // ServiceRequest, Task, Project, etc.
            $table->unsignedBigInteger('documentable_id')->nullable(); // ID of the parent entity
            $table->index(['documentable_type', 'documentable_id']);
            
            // Legacy support for existing relationships
            $table->unsignedBigInteger('taskID')->nullable();
            $table->unsignedBigInteger('service_request_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            
            // File information
            $table->string('fileName', 255); // Original filename
            $table->string('filePath', 500); // Storage path
            $table->string('fileType', 100); // MIME type
            $table->unsignedBigInteger('fileSize')->default(0); // Size in bytes
            
            // Document metadata
            $table->string('document_type', 50)->nullable(); // 'requirement', 'deliverable', 'reference', 'contract', etc.
            $table->text('description')->nullable(); // Optional description
            $table->boolean('is_public')->default(false); // Whether client can see this
            $table->boolean('is_archived')->default(false); // Soft archive
            
            // User tracking
            $table->unsignedBigInteger('uploaded_by')->nullable(); // User who uploaded
            $table->timestamp('uploadedAt')->nullable(); // Upload timestamp
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin who verified
            $table->timestamp('verified_at')->nullable(); // Verification timestamp
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('taskID')
                ->references('taskID')
                ->on('tasks')
                ->cascadeOnDelete();
                
            $table->foreign('service_request_id')
                ->references('id')
                ->on('service_requests')
                ->cascadeOnDelete();
                
            $table->foreign('project_id')
                ->references('projectID')
                ->on('projects')
                ->cascadeOnDelete();
                
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('uploaded_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
