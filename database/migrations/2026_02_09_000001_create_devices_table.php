<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->string('platform')->default('ios');
            $table->string('model')->nullable();
            $table->string('os_version')->nullable();
            $table->timestamps();

            $table->index('device_id');
        });

        // Add device_id to documents
        Schema::table('documents', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('user_id');
            $table->index('device_id');
        });

        // Add device_id to summaries
        Schema::table('summaries', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('user_id');
            $table->index('device_id');
        });

        // Add device_id to subscriptions
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('user_id');
            $table->index('device_id');
        });

        // Add device_id to usage_logs
        Schema::table('usage_logs', function (Blueprint $table) {
            $table->string('device_id')->nullable()->after('user_id');
            $table->index('device_id');
        });
    }

    public function down(): void
    {
        Schema::table('usage_logs', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });
        Schema::dropIfExists('devices');
    }
};
