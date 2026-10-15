<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'transaction_code',
        'type',
        'amount',
        'transaction_date',
        'source',
        'description',
        'evidence_path',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            FinancialCategory::class,
            'category_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donation(): HasOne
    {
        return $this->hasOne(
            Donation::class,
            'financial_transaction_id'
        );
    }

    public function businessUnitSale(): HasOne
    {
        return $this->hasOne(
            BusinessUnitSale::class,
            'financial_transaction_id'
        );
    }
}
