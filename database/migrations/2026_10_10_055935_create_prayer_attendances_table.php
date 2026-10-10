
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();

            $table->date('attendance_date');
            $table->enum('prayer_name', [
                'subuh',
                'dzuhur',
                'ashar',
                'maghrib',
                'isya',
            ]);

            $table->enum('status', [
                'hadir',
                'izin',
                'sakit',
            ])->default('hadir');

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Satu jamaah hanya memiliki satu catatan
            // untuk waktu salat yang sama pada tanggal yang sama.
            $table->unique([
                'jamaah_id',
                'attendance_date',
                'prayer_name',
            ]);

            $table->index(['attendance_date', 'prayer_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_attendances');
    }
};
