<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ngo_supporters')) {
            return;
        }

        Schema::create('ngo_supporters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Social graph: a user can "follow" (subscribe to updates) and/or
            // "support" (publicly back the cause) an NGO. Works for every role.
            $table->boolean('is_following')->default(true);
            $table->boolean('is_supporting')->default(false);
            $table->timestamp('followed_at')->nullable();
            $table->timestamp('supported_at')->nullable();
            $table->timestamps();

            $table->unique(['ngo_id', 'user_id']);
            $table->index(['user_id', 'is_following']);
            $table->index(['ngo_id', 'is_supporting']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_supporters');
    }
};
