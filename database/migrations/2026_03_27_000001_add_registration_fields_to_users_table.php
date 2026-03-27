<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('users', 'state_id')) {
                $table->foreignId('state_id')->nullable()->after('city_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('users', 'district_id')) {
                $table->foreignId('district_id')->nullable()->after('state_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code', 20)->nullable()->after('district_id');
            }
            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('postal_code');
            }
            if (! Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 20)->nullable()->after('date_of_birth');
            }
            if (! Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type', 50)->nullable()->after('gender');
            }
            if (! Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }
            if (! Schema::hasColumn('users', 'ngo_id')) {
                $table->foreignId('ngo_id')->nullable()->after('user_type')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('users', 'corporate_id')) {
                $table->foreignId('corporate_id')->nullable()->after('ngo_id')->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [
                'first_name',
                'last_name',
                'state_id',
                'district_id',
                'postal_code',
                'date_of_birth',
                'gender',
                'user_type',
                'phone_verified_at',
                'ngo_id',
                'corporate_id',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
