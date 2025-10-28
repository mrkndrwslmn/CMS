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
        Schema::table('users', function (Blueprint $table) {
            $table->string('auth0_id')->nullable()->unique()->after('email');
            $table->enum('auth_provider', ['local', 'auth0'])->default('local')->after('auth0_id');
            $table->json('auth0_profile')->nullable()->after('auth_provider');
            $table->timestamp('last_auth0_sync')->nullable()->after('auth0_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['auth0_id', 'auth_provider', 'auth0_profile', 'last_auth0_sync']);
        });
    }
};
