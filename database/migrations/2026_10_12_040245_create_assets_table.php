<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string('asset_code')->nullable()->unique();
            $table->string('name');
            $table->string('category');
            $table->enum('condition', [
                'baik',
                'perlu_perbaikan',
                'rusak',
            ])->default('baik');

            $table->string('location')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->decimal('purchase_value', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['category', 'condition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
