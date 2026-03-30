<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ensures founder / co-founder columns exist on ngos (idempotent).
 * Fixes SQLSTATE[42S22] when an older DB never ran 2026_03_27_000019.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ngos')) {
            return;
        }

        if (! Schema::hasColumn('ngos', 'founder_name')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->string('founder_name')->nullable()->after('slug');
            });
        }
        if (! Schema::hasColumn('ngos', 'founder_phone')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->string('founder_phone', 20)->nullable()->after('founder_name');
            });
        }
        if (! Schema::hasColumn('ngos', 'founder_email')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->string('founder_email')->nullable()->after('founder_phone');
            });
        }
        if (! Schema::hasColumn('ngos', 'cofounder_name')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->string('cofounder_name')->nullable()->after('founder_email');
            });
        }
        if (! Schema::hasColumn('ngos', 'cofounder_phone')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->string('cofounder_phone', 20)->nullable()->after('cofounder_name');
            });
        }
        if (! Schema::hasColumn('ngos', 'cofounder_email')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->string('cofounder_email')->nullable()->after('cofounder_phone');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('ngos')) {
            return;
        }

        foreach ([
            'cofounder_email',
            'cofounder_phone',
            'cofounder_name',
            'founder_email',
            'founder_phone',
            'founder_name',
        ] as $column) {
            if (Schema::hasColumn('ngos', $column)) {
                Schema::table('ngos', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
