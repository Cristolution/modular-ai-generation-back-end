<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('template_id')->nullable();
            $table->uuid('project_id')->nullable();
            $table->uuid('user_id');
            $table->enum('layer', ['slide', 'style', 'layout', 'content', 'context', 'rules', 'meta', 'asset']);
            $table->string('name');
            $table->string('extension', 10);
            $table->integer('sort_order')->default(0);
            $table->longText('content')->nullable();
            $table->string('storage_url')->nullable();
            $table->unsignedInteger('size_bytes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['template_id']);
            $table->index(['project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
