<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'product_id', 'qty', 'subtotal'];

    // Relasi: Detail ini merujuk ke produk tertentu
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}