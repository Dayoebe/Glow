<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->string('application_type', 20)->default('job')->after('application_code')->index();
            $table->string('department')->nullable()->after('location');
            $table->string('education_level')->nullable()->after('department');
            $table->string('institution')->nullable()->after('education_level');
            $table->string('course_of_study')->nullable()->after('institution');
            $table->text('skills')->nullable()->after('course_of_study');
            $table->longText('motivation')->nullable()->after('skills');
            $table->longText('contribution')->nullable()->after('motivation');
            $table->string('availability')->nullable()->after('available_from');
            $table->string('commitment_length')->nullable()->after('availability');
            $table->boolean('consent')->default(false)->after('user_agent');
            $table->foreignId('career_position_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->foreignId('career_position_id')->nullable(false)->change();
            $table->dropIndex(['application_type']);
            $table->dropColumn([
                'application_type', 'department', 'education_level', 'institution',
                'course_of_study', 'skills', 'motivation', 'contribution',
                'availability', 'commitment_length', 'consent',
            ]);
        });
    }
};
