<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemVariantRequest;
use App\Http\Resources\ItemVariantResource;
use App\Models\ItemVariant;
// use Illuminate\Http\Request;

class ItemVariantController extends Controller
{
    public function index()
    {
        return ItemVariant::with('values', 'item')
            ->get()
            ->map(function ($variant) {
                return new ItemVariantResource($variant);
            });
    }
    public function store(StoreItemVariantRequest $request)
    {
        $data = $request->validated();

        $itemVariant = ItemVariant::create([
            'item_id' => $data['item_id'],
            'sku' => $data['sku'],
            'stock' => $data['stock'],
        ]);
        $itemVariant->values()->sync($data['values']);
        return response()->json($itemVariant, 201);
    }
}

