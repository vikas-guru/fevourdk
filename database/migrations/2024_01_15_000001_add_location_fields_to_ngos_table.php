<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            // Add state_id and district_id before city_id
            $table->unsignedBigInteger('state_id')->nullable()->after('address');
            $table->unsignedBigInteger('district_id')->nullable()->after('state_id');
            
            // Add foreign key constraints
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('state_id');
            $table->index('district_id');
            $table->index(['state_id', 'district_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['state_id', 'district_id', 'city_id']);
            $table->dropIndex('district_id');
            $table->dropIndex('state_id');
            
            // Drop foreign key constraints
            $table->dropForeign(['district_id']);
            $table->dropForeign(['state_id']);
            
            // Drop columns
            $table->dropColumn('district_id');
            $table->dropColumn('state_id');
        });
    }
};
