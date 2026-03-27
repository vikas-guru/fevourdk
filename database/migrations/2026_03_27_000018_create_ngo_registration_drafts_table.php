<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_registration_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('draft_id', 80)->unique();
            $table->json('payload')->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('last_saved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_registration_drafts');
    }
};
