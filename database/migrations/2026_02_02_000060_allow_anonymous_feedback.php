<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->makeUserIdNullable('news_comments');
        $this->makeUserIdNullable('event_comments');
        $this->makeUserIdNullable('podcast_comments');
        $this->makeUserIdNullable('show_reviews');
        $this->makeUserIdNullable('podcast_reviews');
    }

    public function down(): void
    {
        // Intentionally left blank to avoid breaking existing anonymous feedback.
    }

    private function makeUserIdNullable(string $table): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        if (!Schema::hasColumn($table, 'user_id')) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            });
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('user_id')->nullable()->change();
        });
    }
};
