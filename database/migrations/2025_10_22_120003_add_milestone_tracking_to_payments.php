<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add milestone and payment type tracking to payments
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Link payment to a specific milestone (if milestone payment type)
            $table->foreignId('milestone_id')
                  ->nullable()
                  ->after('service_request_id')
                  ->constrained('project_milestones')
                  ->onDelete('set null');
            
            // Payment type classification
            $table->enum('payment_type', [
                'full_payment',         // Full project payment
                'milestone_payment',    // Payment for a specific phase
                'downpayment',         // Initial downpayment
                'remaining_balance'    // Final balance payment for downpayment type
            ])->nullable()->after('milestone_id');
            
            // Add indexes for efficient queries
            $table->index(['milestone_id', 'status']);
            $table->index(['payment_type', 'status']);
            $table->index(['service_request_id', 'payment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['milestone_id', 'status']);
            $table->dropIndex(['payment_type', 'status']);
            $table->dropIndex(['service_request_id', 'payment_type']);
            $table->dropForeign(['milestone_id']);
            $table->dropColumn(['milestone_id', 'payment_type']);
        });
    }
};
