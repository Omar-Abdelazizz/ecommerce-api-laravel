<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ItemVariant;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.itemVariant.values', 'items.itemVariant.item')
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart) {
            return response()->json([
                'items' => []
            ]);
        }
        return response()->json([
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'items' => $cart->items->map(function ($items) {
                return [
                    'id' => $items->id,
                    'item' => $items->itemVariant->item->name,
                    'sku' => $items->itemVariant->sku,
                    'quantity' => $items->quantity,
                    'price' => $items->itemVariant->item->price,
                    'values' => $items->itemVariant->values->map(function ($v) {
                        return [
                            'key' => $v->type,
                            'value' => $v->value
                        ];
                    })->values()
                ];
            })->values()
        ]);
    }



    public function add(Request $request)
    {
        $data = $request->validate([
            'item_variant_id' => 'required|exists:item_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ItemVariant::findOrFail($data['item_variant_id']);

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('item_variant_id', $data['item_variant_id'])
            ->first();

        $currentQty = $item ? $item->quantity : 0;
        $newQty = $currentQty + $data['quantity'];

        if ($item) {
            $item->update([
                'quantity' => $newQty
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'item_variant_id' => $data['item_variant_id'],
                'quantity' => $data['quantity']
            ]);
        }


        return response()->json(['message' => 'Added to cart']);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'item_variant_id' => 'required|exists:item_variants,id',
        ]);
        $cart = Cart::where('user_id', auth()->id())->first();
        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found'
            ]);
        }
        $item = CartItem::where('cart_id', $cart->id)
            ->where('item_variant_id', $data['item_variant_id'])
            ->first();

        if ($item) {
            $item->delete();
        } else {
            return response()->json([
                'message' => 'Item Not Found'
            ]);
        }
        return response()->json(['message' => 'Removed from cart']);
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Not yours to delete'
            ]);
        }
        $cart->delete();
        return response()->json(['message' => 'Cart Deleted']);
    }

    public function update(Request $request, $item_variant_id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        $cart = Cart::where('user_id', auth()->id())->first();
        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found'
            ]);
        }
        $item = CartItem::where('cart_id', $cart->id)
            ->where('item_variant_id', $item_variant_id)
            ->first();
        if (!$item) {
            return response()->json([
                'message' => 'Item not in cart'
            ]);
        }
        $variant = $item->itemVariant;
        if ($data['quantity'] > $variant->stock) {
            return response()->json([
                'message' => 'Not enough stock'
            ]);
        }
        $item->update([
            'quantity' => $data['quantity'],
        ]);
        return response()->json([
            'message' => 'Item quantity updated'
        ]);
    }
}