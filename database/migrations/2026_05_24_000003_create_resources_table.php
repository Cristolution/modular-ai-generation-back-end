<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('forked_from_id')->nullable();
            $table->string('kind');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('content');
            $table->json('placeholders')->nullable();
            $table->string('visibility')->default('private');
            $table->json('tags')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('forked_from_id')->references('id')->on('resources')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};