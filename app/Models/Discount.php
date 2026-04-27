<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{


    protected $fillable = [
        'type',
        'discounted_id',
        'value_type',
        'min_quantity',
        'min_price',
        'value',
        'starts_at',
        'expires_at'
    ];
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'discounted_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'discounted_id');
    }

    public function scopeActive($query)
    {
        return $query->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now());
    }

    public function appliesTo($cart)
    {
        $quantityCon = $this->min_quantity ? $cart->items->sum('quantity') >= $this->min_quantity : false;
        $priceCon = $this->min_price ? $cart->items->sum(function ($item) {
            return $item->itemVariant->item->price * $item->quantity;
        }) >= $this->min_price : false;

        return $quantityCon || $priceCon;
    }

    public function appliesToItem($item)
{
    if ($this->type === 'item') {
        return $this->discounted_id === $item->id;
    }

    if ($this->type === 'category') {
        return $this->discounted_id === $item->category_id;
    }

    return false;
}

    public function calcDiscount($total)
    {
        if ($this->value_type === 'percentage') {
            return $total * $this->value / 100;
        }
        return $this->value;
    }
}