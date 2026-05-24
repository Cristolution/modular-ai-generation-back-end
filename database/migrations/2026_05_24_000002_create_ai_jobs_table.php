<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('file_id')->nullable();
            $table->uuid('project_id')->nullable();
            $table->uuid('template_id')->nullable();
            $table->uuid('triggered_by')->nullable();
            $table->uuid('provider_id')->nullable();
            $table->string('provider');
            $table->string('model');
            $table->string('layer')->nullable();
            $table->text('prompt')->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('completed_at')->nullable();

            $table->foreign('file_id')->references('id')->on('files')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
            $table->foreign('triggered_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('provider_id')->references('id')->on('user_ai_providers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_jobs');
    }
};