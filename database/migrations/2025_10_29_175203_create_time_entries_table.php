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
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('billable_minutes')->nullable()->comment('Billable portion of duration');
            $table->integer('non_billable_minutes')->nullable()->comment('Non-billable portion');
            $table->boolean('is_capped')->default(false)->comment('Whether capped due to max_hours');
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('calculated_amount', 10, 2)->nullable();
            $table->boolean('is_billable')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('payout_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['task_id', 'adiutor_id']);
            $table->index(['project_id', 'adiutor_id']);
            $table->index(['adiutor_id', 'start_time']);
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
