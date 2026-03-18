<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('notifications_enabled')->default(true)->after('os_version');
            $table->boolean('dark_mode_enabled')->default(false)->after('notifications_enabled');
            // App UI language (BCP-47, e.g. en, en-GB, tr, zh-Hans, zh-Hant)
            $table->string('ui_language', 10)->default('en')->after('dark_mode_enabled');
            // AI output language (BCP-47). Used for summaries/translations.
            $table->string('output_language', 10)->default('en')->after('ui_language');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn([
                'notifications_enabled',
                'dark_mode_enabled',
                'ui_language',
                'output_language',
            ]);
        });
    }
};

