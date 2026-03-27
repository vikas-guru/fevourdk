<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id', 255)->nullable()->index();
            $table->string('route_name', 150)->nullable()->index();
            $table->string('path', 500)->index();
            $table->string('full_url', 1000);
            $table->string('referrer', 1000)->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('country_code', 8)->nullable()->index();
            $table->string('region', 150)->nullable()->index();
            $table->string('city', 150)->nullable()->index();
            $table->string('accept_language', 255)->nullable();
            $table->string('device_type', 20)->nullable()->index();
            $table->string('browser_name', 50)->nullable()->index();
            $table->string('os_name', 50)->nullable()->index();
            $table->smallInteger('status_code')->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->timestamp('viewed_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_page_views');
    }
};

