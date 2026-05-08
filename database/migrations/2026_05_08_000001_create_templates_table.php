<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('type_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('private');
            $table->json('tags')->nullable();
            $table->string('locale')->default('en');
            $table->enum('direction', ['ltr', 'rtl'])->default('ltr');
            $table->unsignedInteger('fork_count')->default(0);
            $table->unsignedInteger('upvote_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->index(['visibility']);
            $table->index(['user_id']);
            $table->index(['type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
