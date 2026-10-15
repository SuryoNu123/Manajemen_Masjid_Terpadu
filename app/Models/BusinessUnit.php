<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'location',
        'manager_name',
        'phone',
        'initial_capital',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'initial_capital' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(BusinessUnitSale::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BusinessUnitItem::class);
    }
}
