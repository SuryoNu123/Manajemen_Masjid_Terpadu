<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'donation_code',
        'donor_name',
        'donor_email',
        'donor_phone',
        'type',
        'amount',
        'payment_method',
        'payment_status',
        'gateway_reference',
        'paid_at',
        'financial_transaction_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function financialTransaction(): BelongsTo
    {
        return $this->belongsTo(
            FinancialTransaction::class,
            'financial_transaction_id'
        );
    }
}
