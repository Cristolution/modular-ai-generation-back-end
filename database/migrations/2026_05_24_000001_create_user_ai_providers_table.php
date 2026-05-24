<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ai_providers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('provider');
            $table->string('display_name')->nullable();
            $table->string('api_key_encrypted')->nullable();
            $table->string('base_url');
            $table->string('default_model')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'provider'], 'idx_user_provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ai_providers');
    }
};