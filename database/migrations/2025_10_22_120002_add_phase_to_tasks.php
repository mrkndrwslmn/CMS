<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add phase tracking to tasks for milestone-based projects
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Link task to a specific milestone phase
            // Nullable because tasks in full_payment/downpayment projects don't have phases
            $table->foreignId('phase_id')
                  ->nullable()
                  ->after('project_id')
                  ->constrained('project_milestones')
                  ->onDelete('set null');
            
            // Add index for efficient phase-based queries
            $table->index(['project_id', 'phase_id']);
            $table->index(['phase_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['project_id', 'phase_id']);
            $table->dropIndex(['phase_id', 'status']);
            $table->dropForeign(['phase_id']);
            $table->dropColumn('phase_id');
        });
    }
};
