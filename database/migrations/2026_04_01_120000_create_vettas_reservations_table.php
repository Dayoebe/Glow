<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vettas_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reservation_code')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 40);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedTinyInteger('guest_count')->default(1);
            $table->enum('status', ['new', 'contacted', 'confirmed', 'completed', 'cancelled'])->default('new');
            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('source')->default('website');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('status');
            $table->index('check_in_date');
            $table->index('check_out_date');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vettas_reservations');
    }
};
