<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('financial_categories')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('transaction_code')->unique();

            $table->enum('type', [
                'income',
                'expense',
            ]);

            $table->decimal('amount', 15, 2);

            $table->date('transaction_date');

            $table->string('source')->nullable();

            $table->string('description')->nullable();

            $table->string('evidence_path')->nullable();

            $table->enum('status', [
                'draft',
                'approved',
                'rejected',
            ])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
