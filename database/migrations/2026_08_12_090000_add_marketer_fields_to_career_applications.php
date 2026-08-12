<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->string('resume_path')->nullable()->change();
            $table->string('resume_original_name')->nullable()->change();
            $table->string('engagement_type', 30)->nullable()->after('application_type');
            $table->string('work_mode', 20)->nullable()->after('engagement_type');
            $table->string('sales_experience')->nullable()->after('skills');
            $table->text('client_network')->nullable()->after('sales_experience');
            $table->text('services_to_promote')->nullable()->after('client_network');
            $table->text('first_lead')->nullable()->after('services_to_promote');
            $table->boolean('commission_acknowledged')->default(false)->after('consent');
        });
    }

    public function down(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->dropColumn([
                'engagement_type', 'work_mode', 'sales_experience', 'client_network',
                'services_to_promote', 'first_lead', 'commission_acknowledged',
            ]);
        });
    }
};
