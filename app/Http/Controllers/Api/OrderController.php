<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\DiscountService;
// use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(DiscountService $discountService)
    {
        $cart = Cart::with('items.itemVariant.item')
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        foreach ($cart->items as $item) {
            $variant = $item->itemVariant;

            if ($item->quantity > $variant->stock) {
                return response()->json([
                    'message' => 'Not enough stock'
                ]);
            }
        }

        $final = $discountService->apply($cart);
        $order = Order::create([
            'user_id' => $cart->user_id,
            'totalPrice' => $final['total']
        ]);

        foreach ($cart->items as $cartItem) {
            $variant = $cartItem->itemVariant;

            $variant->stock -= $cartItem->quantity;
            $variant->save();

            OrderItem::create([
                'order_id' => $order->id,
                'item_variant_id' => $variant->id,
                'quantity' => $cartItem->quantity,
                'price_paid' => $variant->item->price
            ]);
        }

        $cart->items()->delete();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
            'originalTotal' => $final['originalTotal'],
            'totalDiscount' => $final['totalDiscount'],
            'total' => $final['total']
        ]);
    }

    public function index()
    {
        $orders = Order::with('items.itemVariant.item')
            ->where('user_id', auth()->id())
            ->get();
                return OrderResource::collection( $orders );
    }
}
