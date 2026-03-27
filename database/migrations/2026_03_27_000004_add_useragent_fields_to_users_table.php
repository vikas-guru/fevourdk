<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('notification_token');
            }
            if (! Schema::hasColumn('users', 'browser_name')) {
                $table->string('browser_name', 50)->nullable()->after('user_agent');
            }
            if (! Schema::hasColumn('users', 'browser_version')) {
                $table->string('browser_version', 50)->nullable()->after('browser_name');
            }
            if (! Schema::hasColumn('users', 'os_name')) {
                $table->string('os_name', 50)->nullable()->after('browser_version');
            }
            if (! Schema::hasColumn('users', 'device_type')) {
                $table->string('device_type', 20)->nullable()->after('os_name');
            }
            if (! Schema::hasColumn('users', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('device_type');
            }
            if (! Schema::hasColumn('users', 'registration_meta')) {
                $table->json('registration_meta')->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'user_agent',
                'browser_name',
                'browser_version',
                'os_name',
                'device_type',
                'ip_address',
                'registration_meta',
            ] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

