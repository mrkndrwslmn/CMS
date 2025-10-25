<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add foreign key constraint for phase_id after project_milestones table is created
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add foreign key constraint to phase_id
            $table->foreign('phase_id')
                  ->references('id')
                  ->on('project_milestones')
                  ->onDelete('set null');
            
            // Add indexes for performance
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
            $table->dropForeign(['phase_id']);
            $table->dropIndex(['project_id', 'phase_id']);
            $table->dropIndex(['phase_id', 'status']);
        });
    }
};
