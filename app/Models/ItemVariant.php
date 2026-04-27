<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItemVariant extends Model
{
    protected $table = 'item_variants';

    protected $fillable = [
        'item_id',
        'sku',
        'stock',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function values(): BelongsToMany
    {
        return $this->belongsToMany(
            Variant::class,
            'item_variant_values',
            'item_variant_id',
            'variant_id'
        );
    }
}
