<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ngo_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained('ngos')->cascadeOnDelete();
            $table->string('kind', 32);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category', 120)->nullable();
            $table->string('asset_tag', 60)->nullable();
            $table->string('serial_number', 120)->nullable();
            $table->decimal('quantity', 12, 2)->default(1);
            $table->string('unit', 24)->nullable();
            $table->decimal('reorder_level', 12, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_amount', 14, 2)->nullable();
            $table->decimal('current_value_estimate', 14, 2)->nullable();
            $table->string('asset_condition', 24)->default('good');
            $table->string('storage_location', 255)->nullable();
            $table->foreignId('custodian_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('supplier_name', 150)->nullable();
            $table->string('invoice_reference', 120)->nullable();
            $table->date('warranty_expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['ngo_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ngo_inventory_items');
    }
};
