<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->nullable()->constrained('jamaahs')->nullOnDelete();
            $table->string('name');
            $table->string('division'); // Kajian & Dakwah, Humas & Media, Sosial, Olahraga, dll
            $table->string('position'); // Ketua, Wakil, Sekretaris, Bendahara, Anggota, dll
            $table->string('phone', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('status', 20)->default('aktif'); // aktif, alumni, nonaktif
            $table->unsignedSmallInteger('joined_year')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['division', 'status']);
        });

        Schema::create('youth_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('division')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('youth_member_id')->nullable()->constrained('youth_members')->nullOnDelete();
            $table->decimal('budget', 15, 2)->default(0);
            $table->enum('status', ['rencana', 'berjalan', 'selesai', 'dibatalkan'])->default('rencana');
            $table->timestamps();

            $table->index(['status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_programs');
        Schema::dropIfExists('youth_members');
    }
};
