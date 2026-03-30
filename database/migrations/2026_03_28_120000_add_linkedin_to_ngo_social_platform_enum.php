<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE ngo_social_channels MODIFY COLUMN platform ENUM('facebook','instagram','google_business','linkedin') NOT NULL");
        DB::statement("ALTER TABLE ngo_social_post_jobs MODIFY COLUMN platform ENUM('facebook','instagram','google_business','linkedin') NOT NULL");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE ngo_social_channels MODIFY COLUMN platform ENUM('facebook','instagram','google_business') NOT NULL");
        DB::statement("ALTER TABLE ngo_social_post_jobs MODIFY COLUMN platform ENUM('facebook','instagram','google_business') NOT NULL");
    }
};
