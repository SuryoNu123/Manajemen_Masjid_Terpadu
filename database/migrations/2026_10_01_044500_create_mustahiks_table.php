<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mustahiks', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->enum('asnaf', [
                'fakir',
                'miskin',
                'amil',
                'muallaf',
                'riqab',
                'gharim',
                'fisabilillah',
                'ibnu_sabil',
            ]);

            $table->string('address')->nullable();

            $table->string('phone')->nullable();

            $table->decimal('amount', 15, 2);

            $table->enum('status', [
                'pending',
                'distributed',
            ])->default('pending');

            $table->date('distributed_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mustahiks');
    }
};
