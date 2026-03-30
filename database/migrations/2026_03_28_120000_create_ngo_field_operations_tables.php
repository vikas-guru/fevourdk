<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_field_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('task_type', 50)->default('field_visit');
            $table->enum('status', ['draft', 'open', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('assigned');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->dateTime('due_at')->nullable();
            $table->decimal('target_latitude', 10, 7)->nullable();
            $table->decimal('target_longitude', 10, 7)->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['ngo_id', 'status']);
            $table->index(['assignee_id', 'status']);
        });

        Schema::create('ngo_field_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('field_task_id')->nullable()->constrained('ngo_field_tasks')->nullOnDelete();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->decimal('distance_meters', 12, 2)->default(0);
            $table->decimal('max_speed_ms', 8, 3)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['ngo_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('ngo_field_track_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_session_id')->constrained('ngo_field_sessions')->cascadeOnDelete();
            $table->timestamp('recorded_at');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('accuracy_m', 8, 2)->nullable();
            $table->decimal('speed_ms', 8, 3)->nullable();
            $table->decimal('heading', 6, 2)->nullable();
            $table->decimal('altitude_m', 8, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['field_session_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_field_track_points');
        Schema::dropIfExists('ngo_field_sessions');
        Schema::dropIfExists('ngo_field_tasks');
    }
};
