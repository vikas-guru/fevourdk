<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->string('theme_color', 20)->default('#2563eb')->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->dropColumn('theme_color');
        });
    }
};
