<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ngos') && ! Schema::hasColumn('ngos', 'finance_show_full_bank_numbers')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->boolean('finance_show_full_bank_numbers')->default(false);
            });
        }

        if (! Schema::hasTable('ngo_outbound_payments')) {
            Schema::create('ngo_outbound_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
                $table->foreignId('payee_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('payee_name', 200)->nullable();
                $table->string('category', 80);
                $table->decimal('amount', 14, 2);
                $table->string('currency', 3)->default('INR');
                $table->string('payment_method', 40)->default('bank_transfer');
                $table->string('status', 24)->default('scheduled');
                $table->timestamp('paid_at')->nullable();
                $table->string('utr_reference', 120)->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('ledger_entry_id')->nullable()->constrained('ngo_ledger_entries')->nullOnDelete();
                $table->timestamps();

                $table->index(['ngo_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_outbound_payments');

        if (Schema::hasTable('ngos') && Schema::hasColumn('ngos', 'finance_show_full_bank_numbers')) {
            Schema::table('ngos', function (Blueprint $table) {
                $table->dropColumn('finance_show_full_bank_numbers');
            });
        }
    }
};
