<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
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
        return ItemResource::collection(Item::with('variants.values', 'category')->paginate());
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

        return new ItemResource($item->load('variants.values', 'category'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update($request->validated());
        return new ItemResource($item->load('variants.values', 'category'));
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
