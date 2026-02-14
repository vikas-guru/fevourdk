<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'staff']);
            $table->string('designation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('corporates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->string('registration_number', 100)->unique();
            $table->string('pan', 20)->unique();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('address');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->json('csr_focus_areas')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('corporate_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('corporate_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'manager', 'staff']);
            $table->string('designation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('donor_type', 20)->default('individual');
            $table->decimal('total_donated', 12, 2)->default(0);
            $table->integer('donation_count')->default(0);
            $table->date('first_donation_date')->nullable();
            $table->date('last_donation_date')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('address');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->enum('vendor_type', ['auditor', 'digital_marketer', 'trainer', 'event_partner']);
            $table->json('services')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->enum('verification_status', ['pending', 'verified', 'suspended'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
