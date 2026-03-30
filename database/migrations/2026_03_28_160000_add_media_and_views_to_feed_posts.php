<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('feed_posts')) {
            return;
        }

        Schema::table('feed_posts', function (Blueprint $table) {
            if (! Schema::hasColumn('feed_posts', 'media')) {
                $table->json('media')->nullable()->after('image_url');
            }
            if (! Schema::hasColumn('feed_posts', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('is_published');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('feed_posts')) {
            return;
        }

        Schema::table('feed_posts', function (Blueprint $table) {
            if (Schema::hasColumn('feed_posts', 'media')) {
                $table->dropColumn('media');
            }
            if (Schema::hasColumn('feed_posts', 'views_count')) {
                $table->dropColumn('views_count');
            }
        });
    }
};
