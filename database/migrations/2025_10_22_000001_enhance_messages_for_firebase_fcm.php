<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Enhances messages table for Firebase FCM integration and project-based conversations
     */
    public function up(): void
    {
        // Add FCM token storage to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('fcm_token')->nullable()->after('remember_token');
            $table->timestamp('fcm_token_updated_at')->nullable()->after('fcm_token');
        });

        // Enhance messages table for better conversation management
        Schema::table('messages', function (Blueprint $table) {
            // Add conversation tracking
            $table->string('conversation_id')->nullable()->after('id');
            
            // Add attachment support
            $table->json('attachments')->nullable()->after('message');
            
            // Add message metadata
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent')->after('is_read');
            $table->timestamp('delivered_at')->nullable()->after('read_at');
            
            // Add soft deletes for message recall
            $table->softDeletes();
            
            // Add indexes for better performance
            $table->index('conversation_id');
            $table->index(['project_id', 'created_at']);
            $table->index('status');
        });

        // Create conversation metadata table
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('conversation_id')->unique();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('last_message_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->timestamp('last_message_at')->nullable();
            $table->integer('unread_count_client')->default(0);
            $table->integer('unread_count_admin')->default(0);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
            
            $table->index(['project_id', 'is_archived']);
            $table->index(['client_id', 'last_message_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
        
        Schema::table('messages', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'conversation_id',
                'attachments',
                'status',
                'delivered_at'
            ]);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fcm_token', 'fcm_token_updated_at']);
        });
    }
};
