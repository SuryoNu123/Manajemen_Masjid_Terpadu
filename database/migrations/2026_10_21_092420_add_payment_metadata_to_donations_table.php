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
        Schema::table('donations', function (Blueprint $table) {
            $table->uuid('status_token')->nullable()->unique();
            $table->unique('gateway_reference');
            $table->unique('financial_transaction_id');
            $table->text('gateway_payment_url')->nullable();
            $table->text('gateway_qr_code_url')->nullable();
            $table->timestamp('payment_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['status_token']);
            $table->dropUnique(['gateway_reference']);
            $table->dropUnique(['financial_transaction_id']);
            $table->dropColumn(['status_token', 'gateway_payment_url', 'gateway_qr_code_url', 'payment_expires_at']);
        });
    }
};
