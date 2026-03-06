<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vettas_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });

        Schema::create('vettas_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('vettas_categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('caption')->nullable();
            $table->longText('description')->nullable();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->string('photographer_name')->nullable();
            $table->string('location')->nullable();
            $table->date('captured_at')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('category_id');
            $table->index('is_featured');
            $table->index('is_published');
            $table->index('published_at');
            $table->index('captured_at');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vettas_photos');
        Schema::dropIfExists('vettas_categories');
    }
};
