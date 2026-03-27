<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'state_name')) {
                $table->string('state_name', 150)->nullable()->after('state_id');
            }
            if (! Schema::hasColumn('users', 'district_name')) {
                $table->string('district_name', 150)->nullable()->after('district_id');
            }
            if (! Schema::hasColumn('users', 'city_name')) {
                $table->string('city_name', 150)->nullable()->after('city_id');
            }
            if (! Schema::hasColumn('users', 'mandal_name')) {
                $table->string('mandal_name', 150)->nullable()->after('city_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['state_name', 'district_name', 'city_name', 'mandal_name'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

