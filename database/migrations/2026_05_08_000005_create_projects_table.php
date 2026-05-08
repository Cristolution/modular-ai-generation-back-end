<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('template_id')->nullable();
            $table->uuid('type_id');
            $table->string('origin_template_name')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('private');
            $table->json('tags')->nullable();
            $table->string('locale')->default('en');
            $table->enum('direction', ['ltr', 'rtl'])->default('ltr');
            $table->timestamp('cloned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->index(['user_id']);
            $table->index(['template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
