<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vettas_categories', function (Blueprint $table) {
            $table->string('eyebrow', 100)->nullable()->after('description');
            $table->string('headline', 180)->nullable()->after('eyebrow');
            $table->string('seo_title', 160)->nullable()->after('headline');
            $table->string('meta_description', 320)->nullable()->after('seo_title');
            $table->json('highlights')->nullable()->after('meta_description');
            $table->json('faqs')->nullable()->after('highlights');
        });
    }

    public function down(): void
    {
        Schema::table('vettas_categories', function (Blueprint $table) {
            $table->dropColumn(['eyebrow', 'headline', 'seo_title', 'meta_description', 'highlights', 'faqs']);
        });
    }
};
