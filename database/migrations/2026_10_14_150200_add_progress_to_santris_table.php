<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->string('progress_hafalan')->nullable()->after('enrollment_date'); // misal: Juz 30 / Surat An-Naba
            $table->string('progress_bacaan')->nullable()->after('progress_hafalan');  // misal: Iqro Jilid 4 / Al-Qur'an Hal 120
            $table->text('learning_notes')->nullable()->after('progress_bacaan');
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn(['progress_hafalan', 'progress_bacaan', 'learning_notes']);
        });
    }
};
