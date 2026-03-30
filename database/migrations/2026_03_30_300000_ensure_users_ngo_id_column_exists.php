<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('ngos')) {
            return;
        }

        if (Schema::hasColumn('users', 'ngo_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('ngo_id')->nullable()->constrained('ngos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'ngo_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ngo_id']);
            $table->dropColumn('ngo_id');
        });
    }
};
