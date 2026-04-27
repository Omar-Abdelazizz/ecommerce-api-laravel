<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'item_variant_id',
        'quantity'
    ];
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function itemVariant(): BelongsTo
    {
        return $this
        ->belongsTo(ItemVariant::class, 'item_variant_id');
    }
}
