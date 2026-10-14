<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'library_book_id',
        'jamaah_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
        'handled_by',
        'returned_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_date' => 'date',
            'returned_at' => 'date',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(
            LibraryBook::class,
            'library_book_id'
        );
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'handled_by'
        );
    }

    public function returnHandler(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'returned_by'
        );
    }
}
