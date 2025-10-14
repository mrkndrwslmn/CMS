<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * PROFILE SYSTEM
     * Consolidates: 2025_10_09_131150_create_adiutor_profiles_table.php + 2025_10_09_133112_create_client_profiles_table.php + 2025_10_92942_add_contact_fields_to_client_profiles_table.php
     */
    public function up(): void
    {
        // Skills table for adiutors
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // programming, design, marketing, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
        });

        // Adiutor profiles with all enhancements
        Schema::create('adiutor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('title')->nullable(); // Professional title
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->json('availability')->nullable(); // Available hours, timezone
            $table->string('portfolio_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->text('experience')->nullable();
            $table->json('languages')->nullable(); // Spoken languages
            $table->string('location')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_projects')->default(0);
            $table->enum('status', ['active', 'inactive', 'busy'])->default('active');
            $table->timestamps();
            
            $table->index(['status', 'is_verified']);
            $table->index('rating');
        });

        // Adiutor skills pivot table
        Schema::create('adiutor_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adiutor_id')->constrained('adiutor_profiles')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            $table->integer('years_experience')->default(0);
            $table->timestamps();
            
            $table->unique(['adiutor_id', 'skill_id']);
            $table->index('proficiency_level');
        });

        // Client profiles with all enhancements including contact fields
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->text('company_description')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable(); // small, medium, large, enterprise
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->json('preferred_contact_methods')->nullable(); // email, phone, messenger
            $table->string('timezone')->nullable();
            $table->json('business_hours')->nullable();
            $table->text('notes')->nullable(); // Internal notes from admin/adiutors
            $table->boolean('is_verified')->default(false);
            $table->integer('total_projects')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->enum('client_type', ['individual', 'small_business', 'enterprise'])->default('individual');
            
            // Contact fields (from 2025_10_92942_add_contact_fields_to_client_profiles_table.php)
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('tax_id')->nullable();
            
            $table->timestamps();
            
            $table->index(['client_type', 'is_verified']);
            $table->index('industry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adiutor_skills');
        Schema::dropIfExists('adiutor_profiles');
        Schema::dropIfExists('client_profiles');
        Schema::dropIfExists('skills');
    }
};