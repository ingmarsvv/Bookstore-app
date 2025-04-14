<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory, HasUuids;

    protected $fillable = ['book_id'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $primaryKey = 'transaction_id';

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
