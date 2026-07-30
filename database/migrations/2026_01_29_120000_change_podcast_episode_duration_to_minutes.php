<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->decimal('duration', 8, 2)->nullable()->change();
        });

        DB::statement('UPDATE podcast_episodes SET duration = ROUND(duration / 60, 2) WHERE duration IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('UPDATE podcast_episodes SET duration = ROUND(duration * 60) WHERE duration IS NOT NULL');
        DB::table('podcast_episodes')->whereNull('duration')->update(['duration' => 0]);

        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->unsignedInteger('duration')->default(0)->change();
        });
    }
};
