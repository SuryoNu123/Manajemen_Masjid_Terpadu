<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->decimal('initial_capital', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_units');
    }
};
