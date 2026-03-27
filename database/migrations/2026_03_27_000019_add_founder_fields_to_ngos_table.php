<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            if (!Schema::hasColumn('ngos', 'founder_name')) {
                $table->string('founder_name')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('ngos', 'founder_phone')) {
                $table->string('founder_phone', 20)->nullable()->after('founder_name');
            }
            if (!Schema::hasColumn('ngos', 'founder_email')) {
                $table->string('founder_email')->nullable()->after('founder_phone');
            }
            if (!Schema::hasColumn('ngos', 'cofounder_name')) {
                $table->string('cofounder_name')->nullable()->after('founder_email');
            }
            if (!Schema::hasColumn('ngos', 'cofounder_phone')) {
                $table->string('cofounder_phone', 20)->nullable()->after('cofounder_name');
            }
            if (!Schema::hasColumn('ngos', 'cofounder_email')) {
                $table->string('cofounder_email')->nullable()->after('cofounder_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $columns = [
                'founder_name',
                'founder_phone',
                'founder_email',
                'cofounder_name',
                'cofounder_phone',
                'cofounder_email',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('ngos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
