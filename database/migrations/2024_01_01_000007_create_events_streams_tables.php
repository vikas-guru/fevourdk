<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description');
            $table->string('featured_image')->nullable();
            $table->string('venue');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('is_online')->default(false);
            $table->string('meeting_link')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('registered_count')->default(0);
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['registered', 'attended', 'cancelled'])->default('registered');
            $table->text('notes')->nullable();
            $table->timestamp('registered_at');
            $table->timestamps();
        });

        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('stream_key')->unique();
            $table->enum('platform', ['youtube', 'facebook', 'custom']);
            $table->string('platform_stream_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->dateTime('scheduled_start_time');
            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('viewer_count')->default(0);
            $table->enum('status', ['scheduled', 'live', 'ended', 'cancelled'])->default('scheduled');
            $table->boolean('enable_donations')->default(true);
            $table->timestamps();
        });

        Schema::create('impact_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title', 200);
            $table->text('summary');
            $table->json('metrics');
            $table->json('beneficiaries')->nullable();
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();
            $table->date('report_date');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
};
