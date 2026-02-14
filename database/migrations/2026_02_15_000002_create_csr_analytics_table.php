<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csr_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('csr_company_profiles')->onDelete('cascade');
            $table->string('year');
            $table->string('quarter');
            $table->decimal('total_funds_distributed', 15, 2);
            $table->decimal('funds_utilized', 15, 2);
            $table->decimal('impact_score', 8, 2);
            $table->integer('ngos_partnered');
            $table->integer('campaigns_supported');
            $table->integer('beneficiaries_reached');
            $table->json('focus_areas')->nullable();
            $table->json('metrics')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'year', 'quarter']);
            $table->index(['company_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csr_analytics');
    }
};
