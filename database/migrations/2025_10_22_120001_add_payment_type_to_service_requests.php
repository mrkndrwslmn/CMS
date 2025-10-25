<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add payment type support to service requests
     * Supports: full_payment, milestone_payment, downpayment
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Payment type configuration
            $table->enum('payment_type', [
                'full_payment',      // Client pays entire amount upfront
                'milestone_payment', // Client pays per phase/milestone
                'downpayment'        // Client pays initial downpayment, then remaining balance
            ])->nullable()->after('approved_budget');
            
            // Downpayment configuration
            $table->decimal('downpayment_percentage', 5, 2)->nullable()->after('payment_type'); // 0.00 to 100.00
            $table->decimal('downpayment_amount', 10, 2)->nullable()->after('downpayment_percentage');
            $table->decimal('remaining_balance', 10, 2)->nullable()->after('downpayment_amount');
            
            // Downpayment tracking
            $table->boolean('downpayment_paid')->default(false)->after('remaining_balance');
            $table->timestamp('downpayment_paid_at')->nullable()->after('downpayment_paid');
            $table->boolean('remaining_balance_paid')->default(false)->after('downpayment_paid_at');
            $table->timestamp('remaining_balance_paid_at')->nullable()->after('remaining_balance_paid');
            
            // Total milestones count (for milestone payment type)
            $table->integer('total_milestones')->nullable()->after('remaining_balance_paid_at');
            
            // Add index for payment_type queries
            $table->index('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropIndex(['payment_type']);
            $table->dropColumn([
                'payment_type',
                'downpayment_percentage',
                'downpayment_amount',
                'remaining_balance',
                'downpayment_paid',
                'downpayment_paid_at',
                'remaining_balance_paid',
                'remaining_balance_paid_at',
                'total_milestones'
            ]);
        });
    }
};
