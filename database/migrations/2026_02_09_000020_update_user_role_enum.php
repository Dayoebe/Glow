<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dj', 'staff', 'corp_member', 'intern', 'user'])
                ->default('user')
                ->change();
        });

        DB::table('users')->where('role', 'dj')->update(['role' => 'staff']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'staff', 'corp_member', 'intern', 'user'])
                ->default('user')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dj', 'staff', 'corp_member', 'intern', 'user'])
                ->default('user')
                ->change();
        });

        DB::table('users')
            ->whereIn('role', ['corp_member', 'intern'])
            ->update(['role' => 'user']);

        DB::table('users')->where('role', 'staff')->update(['role' => 'dj']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dj', 'user'])
                ->default('user')
                ->change();
        });
    }
};
