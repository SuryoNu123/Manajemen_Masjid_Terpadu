<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_unit_sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_unit_id')
                ->constrained('business_units')
                ->restrictOnDelete();

            $table->date('sale_date');
            $table->string('reference_number')->nullable();
            $table->string('payment_method')->default('tunai');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();

            // Akan digunakan saat integrasi dengan keuangan Modul A.
            $table->foreignId('financial_transaction_id')
                ->nullable()
                ->unique()
                ->constrained('financial_transactions')
                ->nullOnDelete();

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['business_unit_id', 'sale_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_unit_sales');
    }
};
