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
        // Only add the most critical indexes to fix the 537ms session query and other performance issues
        
        // Add composite index for time_entries - most important for your app
        if (!Schema::hasIndex('time_entries', 'time_entries_adiutor_started_index')) {
            Schema::table('time_entries', function (Blueprint $table) {
                $table->index(['adiutor_id', 'start_time'], 'time_entries_adiutor_started_index');
            });
        }

        // Add index for finding active timers
        if (!Schema::hasIndex('time_entries', 'time_entries_adiutor_ended_index')) {
            Schema::table('time_entries', function (Blueprint $table) {
                $table->index(['adiutor_id', 'end_time'], 'time_entries_adiutor_ended_index');
            });
        }

        // Add task status index for available tasks query
        if (!Schema::hasIndex('tasks', 'tasks_status_priority_index')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['status', 'priority'], 'tasks_status_priority_index');
            });
        }

        // Add users email index if it doesn't exist
        if (!Schema::hasIndex('users', 'users_email_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email', 'users_email_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->dropIndex('time_entries_adiutor_started_index');
            $table->dropIndex('time_entries_adiutor_ended_index');
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_status_priority_index');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_index');
        });
    }
};
