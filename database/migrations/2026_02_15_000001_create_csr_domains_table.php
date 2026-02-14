<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csr_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('csr_company_profiles')->onDelete('cascade');
            $table->string('domain_name');
            $table->string('domain_url')->nullable();
            $table->string('verification_status')->default('pending');
            $table->text('verification_documents')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'domain_name']);
            $table->index(['domain_name', 'verification_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csr_domains');
    }
};
