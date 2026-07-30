<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('news') || Schema::hasColumn('news', 'featured_position')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $table->string('featured_position')->default('none')->after('is_featured');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('news') || !Schema::hasColumn('news', 'featured_position')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('featured_position');
        });
    }
};
