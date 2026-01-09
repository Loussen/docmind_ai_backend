<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('plan', ['free', 'pro', 'pro_plus'])->default('free');
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('active');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('apple_transaction_id')->nullable();
            $table->string('apple_original_transaction_id')->nullable();
            $table->string('apple_product_id')->nullable();
            $table->boolean('is_auto_renewing')->default(false);
            $table->json('receipt_data')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('apple_original_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

