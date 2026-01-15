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
        // Update blog_posts table if needed
        if (Schema::hasTable('blog_posts')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                if (!Schema::hasColumn('blog_posts', 'audio_url')) {
                    $table->string('audio_url')->nullable()->after('video_url');
                }
            });
        }

        // Update blog_comments table if needed
        if (Schema::hasTable('blog_comments')) {
            Schema::table('blog_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('blog_comments', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->after('post_id')->constrained('blog_comments')->onDelete('cascade');
                }
                if (!Schema::hasColumn('blog_comments', 'author_name')) {
                    $table->string('author_name')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('blog_comments', 'author_email')) {
                    $table->string('author_email')->nullable()->after('author_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('blog_posts')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                if (Schema::hasColumn('blog_posts', 'audio_url')) {
                    $table->dropColumn('audio_url');
                }
            });
        }

        if (Schema::hasTable('blog_comments')) {
            Schema::table('blog_comments', function (Blueprint $table) {
                if (Schema::hasColumn('blog_comments', 'parent_id')) {
                    $table->dropForeign(['parent_id']);
                    $table->dropColumn('parent_id');
                }
                if (Schema::hasColumn('blog_comments', 'author_name')) {
                    $table->dropColumn('author_name');
                }
                if (Schema::hasColumn('blog_comments', 'author_email')) {
                    $table->dropColumn('author_email');
                }
            });
        }
    }
};