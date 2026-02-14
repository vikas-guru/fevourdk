<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('csr_company_profiles', function (Blueprint $table) {
            $table->id();
            
            // Company Information
            $table->string('company_name');
            $table->enum('company_type', ['mnc', 'smc', 'startup', 'non_profit']);
            $table->string('industry_sector');
            $table->string('registration_number');
            $table->string('pan_number', 10);
            $table->string('gst_number', 15)->nullable();
            $table->string('company_phone', 20);
            $table->string('website')->nullable();
            $table->integer('established_year');
            $table->integer('employee_count');
            $table->decimal('annual_turnover', 15, 2);
            $table->decimal('csr_budget', 15, 2);
            
            // Address Information
            $table->text('registered_office_address');
            $table->text('corporate_office_address');
            
            // CSR Information
            $table->json('csr_focus_areas');
            $table->enum('partnership_type', ['funding', 'technical', 'volunteer', 'mentorship']);
            $table->text('previous_csr_activities')->nullable();
            
            // File Attachments
            $table->string('company_logo')->nullable();
            $table->string('company_brochure')->nullable();
            $table->string('registration_certificate')->nullable();
            
            // Status
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['verification_status', 'created_at']);
            $table->index('company_type');
            $table->index('industry_sector');
        });
    }

    public function down()
    {
        Schema::dropIfExists('csr_company_profiles');
    }
};
