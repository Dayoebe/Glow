<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // News Categories Table
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('fas fa-newspaper');
            $table->string('color')->default('emerald');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });

        // News Table
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            
            $table->foreignId('category_id')->constrained('news_categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamp('published_at')->nullable();
            $table->string('read_time')->nullable();
            
            // Stats
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            $table->unsignedBigInteger('shares')->default(0);
            
            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->enum('breaking', ['no', 'breaking', 'urgent'])->default('no');
            $table->timestamp('breaking_until')->nullable();
            
            // SEO
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->json('tags')->nullable();
            
            $table->timestamps();

            $table->index('slug');
            $table->index('category_id');
            $table->index('author_id');
            $table->index('is_published');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index('breaking');
        });

        // News Comments Table
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->boolean('is_approved')->default(true); // Auto-approve by default
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index('news_id');
            $table->index('user_id');
            $table->index('is_approved');
        });

        // News Interactions Table (consolidates reactions, bookmarks, views, shares)
        Schema::create('news_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->enum('type', ['view', 'reaction', 'bookmark', 'share']);
            $table->string('value')->nullable(); // reaction type, platform for share, etc.
            $table->text('notes')->nullable(); // for bookmark notes
            $table->string('collection')->nullable(); // for bookmark collections
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['news_id', 'type']);
            $table->index(['user_id', 'type']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_interactions');
        Schema::dropIfExists('news_comments');
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_categories');
    }
};