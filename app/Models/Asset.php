<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'category',
        'condition',
        'location',
        'acquisition_date',
        'purchase_value',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
            'purchase_value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }
}
