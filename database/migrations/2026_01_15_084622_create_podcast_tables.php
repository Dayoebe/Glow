<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Podcast Shows (like TV series for podcasts)
        Schema::create('podcast_shows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->string('host_name');
            $table->foreignId('host_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('co_hosts')->nullable(); // Array of user IDs
            $table->string('category'); // Music, Talk, Interview, Tech, etc.
            $table->enum('frequency', ['daily', 'weekly', 'biweekly', 'monthly', 'irregular'])->default('weekly');
            $table->string('language')->default('en');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('explicit')->default(false);
            $table->json('tags')->nullable();
            $table->string('rss_feed_url')->nullable(); // For external distribution
            $table->string('spotify_url')->nullable();
            $table->string('apple_url')->nullable();
            $table->string('google_url')->nullable();
            $table->unsignedBigInteger('total_episodes')->default(0);
            $table->unsignedBigInteger('total_plays')->default(0);
            $table->unsignedBigInteger('subscribers')->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category');
            $table->index('is_featured');
        });

        // Podcast Episodes
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('podcast_shows')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('show_notes')->nullable(); // Rich text with timestamps
            $table->string('cover_image')->nullable(); // Episode-specific cover
            $table->string('audio_file'); // Main audio file URL/path
            $table->string('audio_format')->default('mp3'); // mp3, m4a, etc.
            $table->unsignedInteger('duration')->default(0); // in seconds
            $table->unsignedBigInteger('file_size')->default(0); // in bytes
            $table->integer('episode_number')->nullable();
            $table->integer('season_number')->nullable();
            $table->enum('episode_type', ['full', 'trailer', 'bonus'])->default('full');
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('explicit')->default(false);
            $table->json('guests')->nullable(); // Guest names/links
            $table->json('chapters')->nullable(); // Podcast chapters with timestamps
            $table->json('transcript')->nullable(); // Auto-generated or manual
            $table->unsignedBigInteger('plays')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->unsignedBigInteger('shares')->default(0);
            $table->decimal('average_listen_duration', 5, 2)->default(0); // Percentage
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('show_id');
            $table->index('slug');
            $table->index('status');
            $table->index('published_at');
            $table->index(['show_id', 'season_number', 'episode_number']);
        });

        // Podcast Plays/Listens (Analytics)
        Schema::create('podcast_plays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('podcast_episodes')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('session_id'); // Unique session
            $table->string('ip_address');
            $table->integer('listen_duration')->default(0); // seconds listened
            $table->integer('total_duration')->default(0); // episode duration
            $table->decimal('completion_rate', 5, 2)->default(0); // percentage
            $table->integer('last_position')->default(0); // Resume position in seconds
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('platform')->nullable(); // web, app, spotify, apple
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('last_listened_at')->nullable();
            $table->boolean('completed')->default(false);
            
            $table->index('episode_id');
            $table->index('user_id');
            $table->index('session_id');
            $table->index('started_at');
            $table->index(['episode_id', 'completed']);
        });

        // Podcast Subscriptions
        Schema::create('podcast_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('show_id')->constrained('podcast_shows')->onDelete('cascade');
            $table->boolean('notifications_enabled')->default(true);
            $table->timestamp('subscribed_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'show_id']);
            $table->index('user_id');
            $table->index('show_id');
        });

        // Podcast Ratings & Reviews
        Schema::create('podcast_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('podcast_shows')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->timestamps();
            
            $table->unique(['show_id', 'user_id']);
            $table->index('show_id');
            $table->index('rating');
        });

        // Podcast Playlists (User-created)
        Schema::create('podcast_playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
        });

        // Playlist Episodes (Many-to-Many)
        Schema::create('podcast_playlist_episodes', function (Blueprint $table) {
            $table->foreignId('playlist_id')->constrained('podcast_playlists')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('podcast_episodes')->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->timestamp('added_at');
            
            $table->primary(['playlist_id', 'episode_id']);
            $table->index('position');
        });

        // Episode Comments
        Schema::create('podcast_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('podcast_episodes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('podcast_comments')->onDelete('cascade');
            $table->text('comment');
            $table->integer('timestamp')->nullable(); // Comment at specific time in episode
            $table->boolean('is_approved')->default(true);
            $table->unsignedInteger('likes')->default(0);
            $table->timestamps();
            
            $table->index('episode_id');
            $table->index('user_id');
            $table->index('parent_id');
        });

        // Podcast Downloads (Track offline downloads)
        Schema::create('podcast_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('podcast_episodes')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->timestamp('downloaded_at');
            
            $table->index('episode_id');
            $table->index('downloaded_at');
        });

        // Listening History (Continue where you left off)
        Schema::create('podcast_listening_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('podcast_episodes')->onDelete('cascade');
            $table->integer('position')->default(0); // Current position in seconds
            $table->boolean('completed')->default(false);
            $table->timestamp('last_listened_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'episode_id']);
            $table->index('user_id');
            $table->index('last_listened_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('podcast_listening_history');
        Schema::dropIfExists('podcast_downloads');
        Schema::dropIfExists('podcast_comments');
        Schema::dropIfExists('podcast_playlist_episodes');
        Schema::dropIfExists('podcast_playlists');
        Schema::dropIfExists('podcast_reviews');
        Schema::dropIfExists('podcast_subscriptions');
        Schema::dropIfExists('podcast_plays');
        Schema::dropIfExists('podcast_episodes');
        Schema::dropIfExists('podcast_shows');
    }
};