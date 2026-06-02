<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Progressive NGO onboarding: an NGO account is created at minimal signup
 * (name + contact + email + phone + password) and the heavier compliance
 * fields are collected later via the setup wizard. These four columns must
 * therefore accept NULL. registration_number and pan keep their UNIQUE index
 * (MySQL permits multiple NULLs under a UNIQUE index, so pending NGOs do not
 * collide).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE ngos MODIFY registration_number VARCHAR(100) NULL');
        DB::statement('ALTER TABLE ngos MODIFY pan VARCHAR(20) NULL');
        DB::statement('ALTER TABLE ngos MODIFY address VARCHAR(255) NULL');
        DB::statement('ALTER TABLE ngos MODIFY city_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Best-effort revert. Rows created via minimal signup may hold NULLs;
        // backfill before rolling back or these statements will fail.
        DB::statement('ALTER TABLE ngos MODIFY registration_number VARCHAR(100) NOT NULL');
        DB::statement('ALTER TABLE ngos MODIFY pan VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE ngos MODIFY address VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE ngos MODIFY city_id BIGINT UNSIGNED NOT NULL');
    }
};
