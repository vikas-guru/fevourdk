<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_social_post_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->string('source_type', 100);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->enum('platform', ['facebook', 'instagram', 'google_business']);
            $table->json('payload');
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['ngo_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_social_post_jobs');
    }
};
