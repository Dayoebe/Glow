<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description');
            $table->longText('responsibilities')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('benefits')->nullable();

            $table->string('department')->nullable();
            $table->string('employment_type')->default('full-time');
            $table->string('workplace_type')->default('onsite');
            $table->string('experience_level')->default('mid');
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->decimal('min_salary', 12, 2)->nullable();
            $table->decimal('max_salary', 12, 2)->nullable();
            $table->string('salary_currency', 10)->default('NGN');
            $table->string('salary_period', 20)->default('monthly');

            $table->date('application_deadline')->nullable();
            $table->date('start_date')->nullable();
            $table->unsignedInteger('positions_available')->default(1);

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->boolean('allow_applications')->default(true);
            $table->string('status')->default('open');
            $table->timestamp('published_at')->nullable();
            $table->json('meta')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('slug');
            $table->index('department');
            $table->index('employment_type');
            $table->index('status');
            $table->index('is_published');
            $table->index('is_featured');
            $table->index('application_deadline');
            $table->index('published_at');
        });

        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_position_id')->constrained('career_positions')->cascadeOnDelete();
            $table->string('application_code')->unique();

            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();

            $table->unsignedInteger('years_experience')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_role')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->date('available_from')->nullable();

            $table->longText('cover_letter')->nullable();
            $table->string('resume_path');
            $table->string('resume_original_name')->nullable();

            $table->string('status')->default('new');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['career_position_id', 'status']);
            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_applications');
        Schema::dropIfExists('career_positions');
    }
};
