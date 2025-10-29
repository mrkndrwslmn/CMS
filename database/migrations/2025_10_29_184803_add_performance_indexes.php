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
        // Critical session performance index - this will fix the 537ms session query
        Schema::table('sessions', function (Blueprint $table) {
            // Add indexes if they don't exist (ignore errors if they already exist)
            try {
                $table->index('id');
            } catch (\Exception $e) {
                // Index already exists, ignore
            }
            try {
                $table->index('user_id');
            } catch (\Exception $e) {
                // Index already exists, ignore
            }
            try {
                $table->index(['user_id', 'last_activity']);
            } catch (\Exception $e) {
                // Index already exists, ignore
            }
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('role');
            $table->index(['role', 'created_at']);
        });

        // Time entries performance indexes
        Schema::table('time_entries', function (Blueprint $table) {
            $table->index('adiutor_id');
            $table->index(['adiutor_id', 'started_at']);
            $table->index(['adiutor_id', 'ended_at']);
            $table->index(['adiutor_id', 'is_approved']);
            $table->index('task_id');
            $table->index(['task_id', 'started_at']);
        });

        // Tasks table indexes
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('status');
            $table->index('project_id');
            $table->index(['project_id', 'status']);
            $table->index(['status', 'priority']);
            $table->index(['project_id', 'status', 'priority']);
        });

        // Projects table indexes
        Schema::table('projects', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });

        // Audit logs indexes for better performance
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index(['user_id', 'created_at']);
        });

        // Feedbacks table indexes
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->index('project_id');
            $table->index(['project_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove session indexes
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'last_activity']);
        });

        // Remove user indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['role']);
            $table->dropIndex(['role', 'created_at']);
        });

        // Remove time_entries indexes
        Schema::table('time_entries', function (Blueprint $table) {
            $table->dropIndex(['adiutor_id']);
            $table->dropIndex(['adiutor_id', 'started_at']);
            $table->dropIndex(['adiutor_id', 'ended_at']);
            $table->dropIndex(['adiutor_id', 'is_approved']);
            $table->dropIndex(['task_id']);
            $table->dropIndex(['task_id', 'started_at']);
        });

        // Remove tasks indexes
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['project_id']);
            $table->dropIndex(['project_id', 'status']);
            $table->dropIndex(['status', 'priority']);
            $table->dropIndex(['project_id', 'status', 'priority']);
        });

        // Remove projects indexes
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'created_at']);
        });

        // Remove audit_logs indexes
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['auditable_type', 'auditable_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        // Remove feedbacks indexes
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['project_id', 'created_at']);
        });
    }
};
