<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_unit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_unit_id')->constrained('business_units')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->string('category')->nullable();
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->string('unit', 20)->default('pcs'); // pcs, botol, pack, dus, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['business_unit_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_unit_items');
    }
};
