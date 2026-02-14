<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('registration_number', 100)->unique();
            $table->string('pan', 20)->unique();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('address');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'suspended'])->default('pending');
            $table->json('focus_areas')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ngo_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->string('document_type', 50);
            $table->string('file_path');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ngo_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_holder_name', 200);
            $table->string('ifsc_code', 20);
            $table->string('branch_address')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ngo_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->enum('gateway_type', ['razorpay', 'phonepe', 'stripe']);
            $table->string('merchant_id');
            $table->text('api_key');
            $table->text('api_secret');
            $table->text('webhook_secret')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }
};
