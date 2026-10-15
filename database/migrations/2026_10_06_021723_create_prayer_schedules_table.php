<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_schedules', function (Blueprint $table) {
            $table->id();

            $table->date('schedule_date');

            $table->enum('prayer_name', [
                'subuh',
                'dzuhur',
                'ashar',
                'maghrib',
                'isya',
            ]);

            $table->time('prayer_time');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('source')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique([
                'schedule_date',
                'prayer_name',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_schedules');
    }
};
