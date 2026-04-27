<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Variant;
// use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Variant::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVariantRequest $request)
    {
        $variant = Variant::create($request->validated());
        return response()->json($variant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        return response()->json($variant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Variant $variant)
    {
        $variant->update($request->validated());
        return response()->json($variant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variant $variant)
    {
        $variant->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
