<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * USERS TABLE
     * Consolidates: 0001_01_01_000000_create_users_table.php + 2025_10_09_124441_update_users_table_for_multi_role_support.php
     */
    public function up(): void
    {
        // Create users table with all enhancements
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullName', 100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'client', 'adiutor']);
            $table->string('phoneNumber', 20)->nullable();
            $table->string('profilePic')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('dateCreated')->default(now());
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['role', 'status']);
            $table->index('email');
        });

        // Password reset tokens table (unchanged from Laravel default)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions table (unchanged from Laravel default)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};