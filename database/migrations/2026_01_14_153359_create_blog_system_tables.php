<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Blog Categories
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('fas fa-newspaper');
            $table->string('color')->default('purple');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Blog Posts
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->string('read_time');
            $table->integer('views')->default(0);
            $table->integer('shares')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->json('tags')->nullable();
            $table->string('series')->nullable();
            $table->integer('series_order')->nullable();
            $table->timestamps();
        });

        // Blog Comments
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('blog_comments')->onDelete('cascade');
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        // Blog Interactions
        Schema::create('blog_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('type'); // view, reaction, bookmark, share
            $table->string('value')->nullable(); // reaction type, share platform
            $table->text('notes')->nullable();
            $table->string('collection')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_interactions');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
    }
};