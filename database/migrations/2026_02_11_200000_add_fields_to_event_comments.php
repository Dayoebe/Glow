<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_comments')) {
            Schema::table('event_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('event_comments', 'author_name')) {
                    $table->string('author_name', 120)->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('event_comments', 'author_email')) {
                    $table->string('author_email', 180)->nullable()->after('author_name');
                }
                if (!Schema::hasColumn('event_comments', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->after('event_id')->constrained('event_comments')->onDelete('cascade');
                }
                if (!Schema::hasColumn('event_comments', 'likes')) {
                    $table->unsignedInteger('likes')->default(0)->after('is_pinned');
                }
                $table->index('parent_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('event_comments')) {
            Schema::table('event_comments', function (Blueprint $table) {
                if (Schema::hasColumn('event_comments', 'likes')) {
                    $table->dropColumn('likes');
                }
                if (Schema::hasColumn('event_comments', 'parent_id')) {
                    $table->dropForeign(['parent_id']);
                    $table->dropColumn('parent_id');
                }
                if (Schema::hasColumn('event_comments', 'author_email')) {
                    $table->dropColumn('author_email');
                }
                if (Schema::hasColumn('event_comments', 'author_name')) {
                    $table->dropColumn('author_name');
                }
            });
        }
    }
};
