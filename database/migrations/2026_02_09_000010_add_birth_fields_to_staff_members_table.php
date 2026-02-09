<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->unsignedTinyInteger('birth_month')->nullable()->after('date_of_birth');
            $table->unsignedTinyInteger('birth_day')->nullable()->after('birth_month');
            $table->unsignedSmallInteger('birth_year')->nullable()->after('birth_day');
            $table->index(['birth_month', 'birth_day'], 'staff_birth_month_day_index');
        });

        DB::table('staff_members')
            ->whereNotNull('date_of_birth')
            ->update([
                'birth_month' => DB::raw('MONTH(date_of_birth)'),
                'birth_day' => DB::raw('DAY(date_of_birth)'),
                'birth_year' => DB::raw('YEAR(date_of_birth)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->dropIndex('staff_birth_month_day_index');
            $table->dropColumn(['birth_month', 'birth_day', 'birth_year']);
        });
    }
};
