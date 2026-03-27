<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_social_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->enum('platform', ['facebook', 'instagram', 'google_business']);
            $table->string('account_handle')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('auto_post_enabled')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['ngo_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_social_channels');
    }
};
