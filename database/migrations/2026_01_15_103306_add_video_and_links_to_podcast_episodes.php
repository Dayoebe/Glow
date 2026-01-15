<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            // Add video support columns after audio_format
            $table->string('video_url')->nullable()->after('audio_format');
            $table->enum('video_type', ['upload', 'youtube', 'vimeo', 'other'])->nullable()->after('video_url');
            
            // Add external platform links
            $table->string('spotify_url')->nullable()->after('video_type');
            $table->string('apple_url')->nullable()->after('spotify_url');
            $table->string('youtube_music_url')->nullable()->after('apple_url');
            $table->string('audiomack_url')->nullable()->after('youtube_music_url');
            $table->string('soundcloud_url')->nullable()->after('audiomack_url');
            $table->json('custom_links')->nullable()->after('soundcloud_url'); // For any other platforms
        });
    }

    public function down(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->dropColumn([
                'video_url', 
                'video_type',
                'spotify_url',
                'apple_url',
                'youtube_music_url',
                'audiomack_url',
                'soundcloud_url',
                'custom_links'
            ]);
        });
    }
};