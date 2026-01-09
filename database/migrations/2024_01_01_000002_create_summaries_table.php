<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('overview');
            $table->json('key_points');
            $table->json('action_items')->nullable();
            $table->json('keywords')->nullable();
            $table->text('important_facts')->nullable();
            $table->text('obligations')->nullable();
            $table->text('risks')->nullable();
            $table->text('findings')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->unsignedInteger('processing_time_ms')->default(0);
            $table->string('language', 10)->default('en');
            $table->string('summary_type')->default('standard');
            $table->timestamps();
            
            $table->foreign('document_id')
                  ->references('id')
                  ->on('documents')
                  ->cascadeOnDelete();
            
            $table->index(['user_id', 'created_at']);
            $table->index('document_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};

