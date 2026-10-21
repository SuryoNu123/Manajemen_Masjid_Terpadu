<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_loan_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_book_id')->constrained()->restrictOnDelete();
            $table->foreignId('jamaah_id')->constrained()->restrictOnDelete();
            $table->foreignId('loan_id')->nullable()->unique()->constrained('book_loans')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['jamaah_id', 'status']);
            $table->index(['library_book_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loan_requests');
    }
};
