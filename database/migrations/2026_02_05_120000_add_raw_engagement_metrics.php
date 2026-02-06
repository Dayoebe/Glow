<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('raw_views')->default(0)->after('views');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->unsignedBigInteger('raw_views')->default(0)->after('views');
        });

        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->unsignedBigInteger('raw_plays')->default(0)->after('plays');
        });
    }

    public function down(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->dropColumn('raw_plays');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('raw_views');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('raw_views');
        });
    }
};
