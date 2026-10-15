<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            $table->string('donation_code')->unique();

            $table->string('donor_name')->nullable();

            $table->string('donor_email')->nullable();

            $table->string('donor_phone')->nullable();

            $table->enum('type', [
                'infaq',
                'sedekah',
                'zakat',
                'qurban',
            ]);

            $table->decimal('amount', 15, 2);

            $table->enum('payment_method', [
                'qris',
                'cash',
                'transfer',
                'other',
            ]);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'expired',
            ])->default('pending');

            $table->string('gateway_reference')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->foreignId('financial_transaction_id')
                ->nullable()
                ->constrained('financial_transactions')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
