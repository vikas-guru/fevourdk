<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngo_registration_drafts', function (Blueprint $table) {
            $table->string('resume_token', 80)->nullable()->unique()->after('draft_id');
            $table->timestamp('resume_email_sent_at')->nullable()->after('last_saved_at');
        });
    }

    public function down(): void
    {
        Schema::table('ngo_registration_drafts', function (Blueprint $table) {
            $table->dropColumn(['resume_token', 'resume_email_sent_at']);
        });
    }
};
