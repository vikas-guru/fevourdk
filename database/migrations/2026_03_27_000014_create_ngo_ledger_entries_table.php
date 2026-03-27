<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->date('entry_date');
            $table->enum('type', ['credit', 'debit']);
            $table->string('category', 100);
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description', 500)->nullable();
            $table->decimal('amount', 14, 2);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['ngo_id', 'entry_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_ledger_entries');
    }
};
