<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // OAPs/Broadcasters (The Talent)
        Schema::create('oaps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('bio');
            $table->string('profile_photo')->nullable();
            $table->json('gallery')->nullable(); // Multiple photos
            $table->string('voice_sample_url')->nullable();
            $table->json('specializations')->nullable(); // [News, Sports, Music, Talk]
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('social_media')->nullable(); // Twitter, Instagram, Facebook
            $table->enum('employment_status', ['full-time', 'part-time', 'contract', 'freelance'])->default('full-time');
            $table->boolean('is_active')->default(true);
            $table->boolean('available')->default(true);
            $table->date('joined_date')->nullable();
            $table->unsignedInteger('total_shows_hosted')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });

        // Show Categories
        Schema::create('show_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('fas fa-microphone');
            $table->string('color')->default('blue');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Shows/Programs
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('full_description')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('promotional_images')->nullable();
            $table->foreignId('category_id')->constrained('show_categories')->onDelete('cascade');
            $table->foreignId('primary_host_id')->nullable()->constrained('oaps')->onDelete('set null');
            $table->json('co_hosts')->nullable(); // Array of OAP IDs
            $table->enum('format', ['live', 'pre-recorded', 'hybrid', 'automated'])->default('live');
            $table->enum('content_rating', ['G', 'PG', 'PG-13', '18+'])->default('G');
            $table->integer('typical_duration')->default(60); // minutes
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_on_demand')->default(true);
            $table->json('tags')->nullable();
            $table->json('sponsors')->nullable(); // Sponsor information
            $table->json('social_media')->nullable();
            $table->string('website_url')->nullable();
            $table->unsignedBigInteger('total_episodes')->default(0);
            $table->unsignedBigInteger('total_listeners')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
        });

        // Show Segments (Structure within a show)
        Schema::create('show_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('start_minute')->default(0); // Minutes from show start
            $table->integer('duration')->default(5); // Duration in minutes
            $table->enum('type', ['intro', 'interview', 'music', 'news', 'ads', 'calls', 'outro', 'other'])->default('other');
            $table->integer('order')->default(0);
            $table->json('notes')->nullable();
            $table->timestamps();
            
            $table->index('show_id');
            $table->index('order');
        });

        // Schedule Slots (Master Schedule)
        Schema::create('schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->foreignId('oap_id')->nullable()->constrained('oaps')->onDelete('set null');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_date')->nullable(); // For limited runs
            $table->date('end_date')->nullable();
            $table->boolean('is_recurring')->default(true);
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->json('exceptions')->nullable(); // Dates to skip
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('show_id');
            $table->index('day_of_week');
            $table->index('start_time');
            $table->index('is_recurring');
        });

        // Show Episodes (Each airing)
        Schema::create('show_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->foreignId('schedule_slot_id')->nullable()->constrained('schedule_slots')->onDelete('set null');
            $table->string('title')->nullable(); // Episode-specific title
            $table->text('description')->nullable();
            $table->longText('show_notes')->nullable();
            $table->dateTime('aired_at');
            $table->integer('actual_duration')->nullable(); // Actual duration in minutes
            $table->string('recording_url')->nullable(); // On-demand playback
            $table->json('guests')->nullable(); // Guest information
            $table->json('segments_aired')->nullable(); // What actually happened
            $table->json('playlist')->nullable(); // Songs/content played
            $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled'])->default('scheduled');
            $table->boolean('is_live')->default(false);
            $table->unsignedBigInteger('listeners')->default(0);
            $table->unsignedBigInteger('peak_listeners')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();
            
            $table->index('show_id');
            $table->index('aired_at');
            $table->index('status');
            $table->index('is_live');
        });

        // Show Guests
        Schema::create('show_guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable(); // Job title
            $table->string('organization')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('social_media')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Episode Guests (Many-to-Many)
        Schema::create('episode_guests', function (Blueprint $table) {
            $table->foreignId('episode_id')->constrained('show_episodes')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('show_guests')->onDelete('cascade');
            $table->string('role')->default('guest'); // guest, expert, correspondent
            $table->integer('appearance_order')->default(1);
            $table->text('notes')->nullable();
            
            $table->primary(['episode_id', 'guest_id']);
        });

        // Show Reviews & Ratings
        Schema::create('show_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            $table->unique(['show_id', 'user_id']);
            $table->index('show_id');
            $table->index('rating');
        });

        // Schedule Templates (Reusable schedules)
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['weekday', 'weekend', 'holiday', 'special', 'emergency'])->default('weekday');
            $table->json('schedule_data'); // The actual schedule configuration
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Broadcast Log (What actually aired - compliance)
        Schema::create('broadcast_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->nullable()->constrained('show_episodes')->onDelete('set null');
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration'); // seconds
            $table->enum('type', ['show', 'music', 'ads', 'station-id', 'emergency'])->default('show');
            $table->text('content_description');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('start_time');
            $table->index('show_id');
            $table->index('type');
        });

        // Show Subscriptions (Follow shows)
        Schema::create('show_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('show_id')->constrained('shows')->onDelete('cascade');
            $table->boolean('notifications_enabled')->default(true);
            $table->timestamp('subscribed_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'show_id']);
        });

        // OAP Availability (Schedule conflicts)
        Schema::create('oap_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oap_id')->constrained('oaps')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_available')->default(true);
            $table->text('reason')->nullable();
            $table->timestamps();
            
            $table->index(['oap_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oap_availability');
        Schema::dropIfExists('show_subscriptions');
        Schema::dropIfExists('broadcast_logs');
        Schema::dropIfExists('schedule_templates');
        Schema::dropIfExists('show_reviews');
        Schema::dropIfExists('episode_guests');
        Schema::dropIfExists('show_guests');
        Schema::dropIfExists('show_episodes');
        Schema::dropIfExists('schedule_slots');
        Schema::dropIfExists('show_segments');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('show_categories');
        Schema::dropIfExists('oaps');
    }
};