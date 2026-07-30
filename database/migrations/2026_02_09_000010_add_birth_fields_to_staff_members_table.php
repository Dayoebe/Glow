<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
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
            ->select(['id', 'date_of_birth'])
            ->orderBy('id')
            ->each(function (object $staff): void {
                $birthDate = Carbon::parse($staff->date_of_birth);

                DB::table('staff_members')
                    ->where('id', $staff->id)
                    ->update([
                        'birth_month' => $birthDate->month,
                        'birth_day' => $birthDate->day,
                        'birth_year' => $birthDate->year,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->dropIndex('staff_birth_month_day_index');
            $table->dropColumn(['birth_month', 'birth_day', 'birth_year']);
        });
    }
};
