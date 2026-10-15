<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();

            $table->enum('period_type', [
                'monthly',
                'yearly',
            ]);

            $table->unsignedTinyInteger('month')->nullable();

            $table->unsignedSmallInteger('year');

            $table->decimal('total_income', 15, 2)->default(0);

            $table->decimal('total_expense', 15, 2)->default(0);

            $table->decimal('balance', 15, 2)->default(0);

            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected',
                'published',
            ])->default('draft');

            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'period_type',
                'month',
                'year',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
