<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Product::with(['category', 'images']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }

        return response()->json($query->paginate(10));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {

        $product = Product::create($request->validated());

        if ($product) {
            return response()->json(['error' => false, 'message' => 'Product created successfully', 'product' => $product], 201);
        }
        return response()->json(['error' => true, 'message' => 'Failed to create product'], 500);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $idProduct)
    {
        $product = Product::findOrFail($idProduct);
        $product->update($request->validated());
        if ($product) {
            return response()->json(['error' => false, 'message' => 'Product updated successfully', 'product' => $product], 200);
        }
        return response()->json(['error' => true, 'message' => 'Failed to update product'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProductRequest $request, $idProduct)
    {
        $request->validated();
        $product = Product::findOrFail($idProduct);
        $product->delete();
        if ($product) {
            return response()->json(['error' => false, 'message' => 'Product deleted successfully', 'product' => $product], 200);
        }
        return response()->json(['error' => true, 'message' => 'Failed to delete product'], 500);

        return response()->json(null, 204);
    }
}
