<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Discount;
use Illuminate\Http\JsonResponse;

class DiscountController extends Controller
{
    public function index(): JsonResponse
    {
        $discounts = Discount::paginate(10);
        return response()->json($discounts);
    }

    public function store(StoreDiscountRequest $request)
    {
        $discount = Discount::create($request->validated());
        return response()->json($discount, 201);
    }

    public function show(Discount $discount)
    {
        return response()->json($discount);
    }

    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $discount->update($request->validated());
        return response()->json($discount);
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return response()->json(['message' => 'Discount is deleted']);
    }

}