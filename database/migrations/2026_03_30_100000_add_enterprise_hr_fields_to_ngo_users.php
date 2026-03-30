<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ngo_users')) {
            return;
        }

        Schema::table('ngo_users', function (Blueprint $table) {
            if (! Schema::hasColumn('ngo_users', 'employee_code')) {
                $table->string('employee_code', 40)->nullable()->after('ngo_id');
            }
            if (! Schema::hasColumn('ngo_users', 'employment_type')) {
                $table->string('employment_type', 32)->nullable()->after('employee_code');
            }
            if (! Schema::hasColumn('ngo_users', 'work_location')) {
                $table->string('work_location', 120)->nullable()->after('employment_type');
            }
        });

        Schema::table('ngo_users', function (Blueprint $table) {
            if (Schema::hasColumn('ngo_users', 'employee_code')) {
                $table->index(['ngo_id', 'employee_code'], 'ngo_users_ngo_employee_code_index');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ngo_users')) {
            return;
        }

        Schema::table('ngo_users', function (Blueprint $table) {
            $table->dropIndex(['ngo_id', 'employee_code']);
        });

        Schema::table('ngo_users', function (Blueprint $table) {
            foreach (['employee_code', 'employment_type', 'work_location'] as $col) {
                if (Schema::hasColumn('ngo_users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
