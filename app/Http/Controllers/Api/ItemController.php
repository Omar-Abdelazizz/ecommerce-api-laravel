<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
// use Request;
// use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Item::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $item = Item::create($request->validated());
        return response()->json($item, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load('variants.values', 'category');

        $variants = $item->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'stock' => $variant->stock,
                'values' => $variant->values->map(function ($values) {
                    return [
                        'key' => $values->type,
                        'value' => $values->value,
                    ];
                })
            ];
        });

        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'category' => $item->category,
            'variants' => $variants,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update($request->validated());
        return $item;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
