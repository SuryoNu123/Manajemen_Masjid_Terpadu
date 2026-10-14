<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();

            $table->string('loan_code')->unique();

            $table->foreignId('library_book_id')
                ->constrained('library_books')
                ->restrictOnDelete();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->restrictOnDelete();

            $table->date('borrowed_at');
            $table->date('due_date');
            $table->date('returned_at')->nullable();

            $table->enum('status', [
                'borrowed',
                'returned',
                'lost',
            ])->default('borrowed');

            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('returned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['jamaah_id', 'status']);
            $table->index(['library_book_id', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
