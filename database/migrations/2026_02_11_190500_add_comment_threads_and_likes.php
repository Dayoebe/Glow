<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('news_comments')) {
            Schema::table('news_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('news_comments', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->after('news_id')->constrained('news_comments')->onDelete('cascade');
                }
                if (!Schema::hasColumn('news_comments', 'likes')) {
                    $table->unsignedInteger('likes')->default(0)->after('is_pinned');
                }
                $table->index('parent_id');
            });
        }

        if (Schema::hasTable('blog_comments')) {
            Schema::table('blog_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('blog_comments', 'likes')) {
                    $table->unsignedInteger('likes')->default(0)->after('is_pinned');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('news_comments')) {
            Schema::table('news_comments', function (Blueprint $table) {
                if (Schema::hasColumn('news_comments', 'likes')) {
                    $table->dropColumn('likes');
                }
                if (Schema::hasColumn('news_comments', 'parent_id')) {
                    $table->dropForeign(['parent_id']);
                    $table->dropColumn('parent_id');
                }
            });
        }

        if (Schema::hasTable('blog_comments')) {
            Schema::table('blog_comments', function (Blueprint $table) {
                if (Schema::hasColumn('blog_comments', 'likes')) {
                    $table->dropColumn('likes');
                }
            });
        }
    }
};
