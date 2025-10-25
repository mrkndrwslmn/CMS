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
        Schema::create('budget_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks', 'taskID')->onDelete('cascade');
            $table->foreignId('adiutor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('current_budget', 10, 2);
            $table->decimal('requested_budget', 10, 2);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('adiutor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_change_requests');
    }
};
