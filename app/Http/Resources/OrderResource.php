<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $originalTotal = $this->items->sum(function ($item) {
            return $item->itemVariant->price * $item->quantity;
        });

    return [
        'id' => $this->id,
        'user_id' => $this->user_id,
        'SubTotal' => $originalTotal,
        'totalDiscount' => $originalTotal - $this->totalPrice,
        'totalPrice' => $this->totalPrice,
        'items' => OrderItemResource::collection($this->whenLoaded('items')),
        'created_at' => $this->created_at,
    ];
    }
}
