<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessUnitSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_unit_id',
        'sale_date',
        'reference_number',
        'payment_method',
        'quantity',
        'amount',
        'description',
        'financial_transaction_id',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'quantity' => 'integer',
            'amount' => 'decimal:2',
        ];
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function financialTransaction(): BelongsTo
    {
        return $this->belongsTo(
            FinancialTransaction::class,
            'financial_transaction_id'
        );
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
