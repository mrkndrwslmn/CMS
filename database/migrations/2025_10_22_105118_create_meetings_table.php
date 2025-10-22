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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('title');
            $table->text('description')->nullable();
            
            // Requested schedule by client
            $table->date('requested_date');
            $table->time('requested_time');
            
            // Rescheduled date/time by admin
            $table->date('rescheduled_date')->nullable();
            $table->time('rescheduled_time')->nullable();
            
            // Final approved date/time
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            
            // Status: pending, approved, rescheduled, rejected, completed, cancelled
            $table->enum('status', ['pending', 'approved', 'rescheduled', 'rejected', 'completed', 'cancelled'])->default('pending');
            
            // Zoom meeting details
            $table->string('zoom_meeting_id')->nullable();
            $table->text('zoom_join_url')->nullable();
            $table->text('zoom_start_url')->nullable();
            $table->string('zoom_password')->nullable();
            
            // Admin notes for rejection or rescheduling
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('project_id');
            $table->index('client_id');
            $table->index('status');
            $table->index('scheduled_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
