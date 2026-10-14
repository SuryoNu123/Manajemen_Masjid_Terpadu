<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                ->constrained('assets')
                ->restrictOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();

            $table->enum('status', [
                'terjadwal',
                'dalam_proses',
                'selesai',
                'dibatalkan',
            ])->default('terjadwal');

            $table->decimal('cost', 15, 2)->default(0);
            $table->string('performed_by')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['scheduled_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
