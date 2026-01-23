<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('show_reviews', function (Blueprint $table) {
            $table->string('device_hash', 64)->nullable()->after('user_id');
            $table->unique(['show_id', 'device_hash'], 'show_reviews_show_device_unique');
        });
    }

    public function down(): void
    {
        Schema::table('show_reviews', function (Blueprint $table) {
            $table->dropUnique('show_reviews_show_device_unique');
            $table->dropColumn('device_hash');
        });
    }
};
