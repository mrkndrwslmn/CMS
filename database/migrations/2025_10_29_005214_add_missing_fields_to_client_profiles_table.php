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
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('industry');
            $table->text('bio')->nullable()->after('address');
            $table->string('linkedin')->nullable()->after('website');
            $table->string('twitter')->nullable()->after('linkedin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->dropColumn(['phone', 'bio', 'linkedin', 'twitter']);
        });
    }
};
