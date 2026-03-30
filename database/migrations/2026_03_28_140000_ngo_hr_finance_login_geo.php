<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->string('login_geo_policy', 32)->default('log_only')->after('digitalization_settings');
            $table->string('login_geo_country_code', 2)->default('IN')->after('login_geo_policy');
            $table->decimal('office_geo_lat', 10, 7)->nullable()->after('login_geo_country_code');
            $table->decimal('office_geo_lng', 10, 7)->nullable()->after('office_geo_lat');
            $table->decimal('office_geo_radius_km', 8, 2)->nullable()->after('office_geo_lng');
            $table->boolean('login_geo_fail_closed')->default(false)->after('office_geo_radius_km');
        });

        Schema::table('ngo_users', function (Blueprint $table) {
            $table->string('job_title', 120)->nullable()->after('designation');
            $table->string('department', 120)->nullable()->after('job_title');
            $table->date('joined_at')->nullable()->after('department');
            $table->foreignId('manager_user_id')->nullable()->after('joined_at')->constrained('users')->nullOnDelete();
        });

        Schema::create('ngo_trusted_login_ips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->string('label', 120)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['ngo_id', 'is_active']);
        });

        Schema::create('user_login_geo_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ngo_id')->nullable()->constrained('ngos')->nullOnDelete();
            $table->string('ip_address', 45);
            $table->string('country_code', 2)->nullable();
            $table->string('region_name', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->decimal('approx_lat', 10, 7)->nullable();
            $table->decimal('approx_lng', 10, 7)->nullable();
            $table->boolean('was_blocked')->default(false);
            $table->string('block_reason', 255)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['ngo_id', 'created_at']);
        });

        Schema::create('ngo_leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->string('name', 80);
            $table->unsignedSmallInteger('default_days_per_year')->nullable();
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['ngo_id', 'is_active']);
        });

        Schema::create('ngo_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('ngo_leave_types')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days', 5, 1);
            $table->string('status', 24)->default('pending');
            $table->text('reason')->nullable();
            $table->foreignId('decided_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->text('decision_notes')->nullable();
            $table->timestamps();

            $table->index(['ngo_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('ngo_expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('category', 80);
            $table->string('description', 500)->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('status', 24)->default('pending');
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->string('ledger_note', 500)->nullable();
            $table->timestamps();

            $table->index(['ngo_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_expense_claims');
        Schema::dropIfExists('ngo_leave_requests');
        Schema::dropIfExists('ngo_leave_types');
        Schema::dropIfExists('user_login_geo_events');
        Schema::dropIfExists('ngo_trusted_login_ips');

        Schema::table('ngo_users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('manager_user_id');
            $table->dropColumn(['job_title', 'department', 'joined_at']);
        });

        Schema::table('ngos', function (Blueprint $table) {
            $table->dropColumn([
                'login_geo_policy',
                'login_geo_country_code',
                'office_geo_lat',
                'office_geo_lng',
                'office_geo_radius_km',
                'login_geo_fail_closed',
            ]);
        });
    }
};
