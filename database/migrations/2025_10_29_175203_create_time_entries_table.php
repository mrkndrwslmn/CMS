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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks', 'taskID')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_minutes')->nullable(); // Calculated or manually entered
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Rate at the time of entry
            $table->decimal('amount', 10, 2)->nullable(); // Calculated: duration * rate
            $table->boolean('is_billable')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['task_id', 'adiutor_id']);
            $table->index(['project_id', 'adiutor_id']);
            $table->index(['adiutor_id', 'started_at']);
            $table->index(['is_billable', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
