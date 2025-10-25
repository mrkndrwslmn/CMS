<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPREHENSIVE PAYMENT SYSTEM
     * Consolidates: 2025_10_14_061611_create_payments_table.php
     */
    public function up(): void
    {
        // Payments table with full payment tracking
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            // Payment details
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // maya, bank_transfer, paypal, stripe, gcash, etc.
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed', 'refunded', 'cancelled'])->default('pending');
            
            // Payment processing
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            $table->json('payment_details')->nullable(); // Store additional payment info
            
            // Transaction tracking (for Maya and other gateways)
            $table->string('transaction_id')->nullable();
            $table->text('gateway_response')->nullable(); // Store Maya/gateway response JSON
            $table->decimal('gateway_fee', 8, 2)->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['service_request_id', 'status']);
            $table->index(['status', 'confirmed_at']);
            $table->index('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};