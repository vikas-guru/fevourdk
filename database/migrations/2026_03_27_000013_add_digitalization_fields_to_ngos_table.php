<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->string('website_url')->nullable()->after('website');
            $table->string('custom_domain')->nullable()->after('website_url');
            $table->enum('custom_domain_status', ['pending', 'verified', 'failed'])->default('pending')->after('custom_domain');

            $table->string('tawk_property_id')->nullable()->after('custom_domain_status');
            $table->string('tawk_widget_id')->nullable()->after('tawk_property_id');

            $table->string('facebook_url')->nullable()->after('tawk_widget_id');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('google_business_location_id')->nullable()->after('instagram_url');
            $table->boolean('google_business_auto_post')->default(false)->after('google_business_location_id');

            $table->json('digitalization_settings')->nullable()->after('google_business_auto_post');
        });
    }

    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->dropColumn([
                'website_url',
                'custom_domain',
                'custom_domain_status',
                'tawk_property_id',
                'tawk_widget_id',
                'facebook_url',
                'instagram_url',
                'google_business_location_id',
                'google_business_auto_post',
                'digitalization_settings',
            ]);
        });
    }
};
