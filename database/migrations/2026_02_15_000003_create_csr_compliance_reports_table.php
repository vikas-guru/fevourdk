<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csr_compliance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('csr_company_profiles')->onDelete('cascade');
            $table->string('report_type');
            $table->string('report_period');
            $table->string('compliance_score');
            $table->json('compliance_metrics')->nullable();
            $table->json('findings')->nullable();
            $table->json('recommendations')->nullable();
            $table->text('report_data')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'report_type']);
            $table->index(['company_id', 'report_period']);
            $table->index('report_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csr_compliance_reports');
    }
};
