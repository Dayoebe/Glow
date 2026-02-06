<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('blog_posts')
            ->where('raw_views', 0)
            ->update(['raw_views' => DB::raw('views')]);

        DB::table('news')
            ->where('raw_views', 0)
            ->update(['raw_views' => DB::raw('views')]);

        DB::table('podcast_episodes')
            ->where('raw_plays', 0)
            ->update(['raw_plays' => DB::raw('plays')]);
    }

    public function down(): void
    {
        // No rollback for backfill.
    }
};
