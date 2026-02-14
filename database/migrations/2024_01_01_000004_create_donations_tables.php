<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description');
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->decimal('target_amount', 12, 2);
            $table->decimal('raised_amount', 12, 2)->default(0);
            $table->integer('donor_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->json('focus_areas')->nullable();
            $table->json('sdg_goals')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->enum('donation_type', ['one_time', 'recurring', 'campaign', 'csr']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->enum('payment_gateway', ['razorpay', 'phonepe', 'stripe']);
            $table->string('gateway_transaction_id')->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('payment_response')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->timestamp('donated_at');
            $table->timestamps();
        });

        Schema::create('recurring_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('frequency', ['monthly', 'quarterly', 'yearly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->string('subscription_id')->unique();
            $table->timestamp('last_donation_at')->nullable();
            $table->timestamp('next_donation_at')->nullable();
            $table->timestamps();
        });

        Schema::create('donation_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ngo_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }
};
