<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notifications_enabled')->default(true)->after('avatar_url');
            $table->boolean('dark_mode_enabled')->default(false)->after('notifications_enabled');
            $table->string('language', 10)->default('en')->after('dark_mode_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notifications_enabled', 'dark_mode_enabled', 'language']);
        });
    }
};
