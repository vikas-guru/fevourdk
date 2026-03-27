<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('feed_posts')) {
            Schema::create('feed_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('ngo_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title');
                $table->text('body');
                $table->string('image_url')->nullable();
                $table->json('meta')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_posts');
    }
};
