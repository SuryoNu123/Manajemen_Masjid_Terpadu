<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santri_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('santri_id')
                ->constrained('santris')
                ->cascadeOnDelete();

            $table->date('attendance_date');
            $table->enum('status', [
                'present',
                'sick',
                'permission',
                'absent',
            ])->default('present');

            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['santri_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri_attendances');
    }
};
