<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Variant extends Model
{
    protected $fillable = [
        'value',
        'type'
    ];
public function itemVariants(): BelongsToMany
{
    return $this->belongsToMany(ItemVariant::class, 'item_variant_values');
}
}
