<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address', 500)->nullable()->after('postal_code');
            }
            if (! Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('address');
            }
            if (! Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (! Schema::hasColumn('users', 'location_permission')) {
                $table->string('location_permission', 20)->nullable()->after('longitude');
            }
            if (! Schema::hasColumn('users', 'notification_permission')) {
                $table->string('notification_permission', 20)->nullable()->after('location_permission');
            }
            if (! Schema::hasColumn('users', 'notification_token')) {
                $table->text('notification_token')->nullable()->after('notification_permission');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'address',
                'latitude',
                'longitude',
                'location_permission',
                'notification_permission',
                'notification_token',
            ] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

