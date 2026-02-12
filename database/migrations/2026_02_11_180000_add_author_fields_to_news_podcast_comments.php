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
                if (!Schema::hasColumn('news_comments', 'author_name')) {
                    $table->string('author_name')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('news_comments', 'author_email')) {
                    $table->string('author_email')->nullable()->after('author_name');
                }
            });
        }

        if (Schema::hasTable('podcast_comments')) {
            Schema::table('podcast_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('podcast_comments', 'author_name')) {
                    $table->string('author_name')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('podcast_comments', 'author_email')) {
                    $table->string('author_email')->nullable()->after('author_name');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('news_comments')) {
            Schema::table('news_comments', function (Blueprint $table) {
                if (Schema::hasColumn('news_comments', 'author_email')) {
                    $table->dropColumn('author_email');
                }
                if (Schema::hasColumn('news_comments', 'author_name')) {
                    $table->dropColumn('author_name');
                }
            });
        }

        if (Schema::hasTable('podcast_comments')) {
            Schema::table('podcast_comments', function (Blueprint $table) {
                if (Schema::hasColumn('podcast_comments', 'author_email')) {
                    $table->dropColumn('author_email');
                }
                if (Schema::hasColumn('podcast_comments', 'author_name')) {
                    $table->dropColumn('author_name');
                }
            });
        }
    }
};
