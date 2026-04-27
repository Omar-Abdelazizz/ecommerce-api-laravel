<?php

namespace App\Services;
use App\Models\Discount;
class DiscountService
{
    public function apply($cart)
    {
        $originalTotal = $cart->items->sum(function ($item) {
            return $item->itemVariant->item->price * $item->quantity;
        });
        $total = $originalTotal;
        $totalDiscount = 0;
        $discounts = Discount::active()->get();

        $itemsWithDiscounts = [];

        foreach ($cart->items as $cartItem) {
            $item = $cartItem->itemVariant->item;
            $itemOriginalPrice = $item->price * $cartItem->quantity;
            $itemDiscount = 0;

            /** @var \App\Models\Discount $discount */
            foreach ($discounts as $discount) {
                if ($discount->appliesTo($cart) && $discount->appliesToItem($item)) {
                    $itemDiscount += $discount->calcDiscount($itemOriginalPrice);
                }
            }

            $itemDiscount = min($itemDiscount, $itemOriginalPrice);
            $itemFinalPrice = $itemOriginalPrice - $itemDiscount;

            $totalDiscount += $itemDiscount;

            $itemsWithDiscounts[] = [
                'cart_item_id' => $cartItem->id,
                'item_id' => $item->id,
                'original_price' => $itemOriginalPrice,
                'discount' => $itemDiscount,
                'final_price' => $itemFinalPrice,
            ];
        }
        $totalDiscount = min($totalDiscount, $originalTotal);
        $total = $originalTotal - $totalDiscount;

        return [
            'total' => max($total, 0),
            'totalDiscount' => $totalDiscount,
            'originalTotal' => $originalTotal,
            'items' => $itemsWithDiscounts,
        ];
    }
}