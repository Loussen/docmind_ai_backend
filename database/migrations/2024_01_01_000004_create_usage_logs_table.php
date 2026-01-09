<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('document_id')->nullable();
            $table->uuid('summary_id')->nullable();
            $table->enum('action', ['upload', 'summarize', 'download']);
            $table->date('usage_date');
            $table->timestamps();
            
            $table->foreign('document_id')
                  ->references('id')
                  ->on('documents')
                  ->nullOnDelete();
            
            $table->foreign('summary_id')
                  ->references('id')
                  ->on('summaries')
                  ->nullOnDelete();
            
            $table->index(['user_id', 'usage_date']);
            $table->index(['user_id', 'action', 'usage_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};

