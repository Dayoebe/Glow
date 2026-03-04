<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $this->setFeaturedImageColumnType('news', 'TEXT');
        $this->setFeaturedImageColumnType('blog_posts', 'TEXT');
        $this->setFeaturedImageColumnType('events', 'TEXT');
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $this->setFeaturedImageColumnType('news', 'VARCHAR(255)');
        $this->setFeaturedImageColumnType('blog_posts', 'VARCHAR(255)');
        $this->setFeaturedImageColumnType('events', 'VARCHAR(255)');
    }

    private function setFeaturedImageColumnType(string $table, string $columnType): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'featured_image')) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` MODIFY `featured_image` {$columnType} NULL");
    }
};
