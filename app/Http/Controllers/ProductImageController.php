<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductImageRequest;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request, $idProduct)
    {

        $data = array_merge($request->validated(), ['product_id' => $idProduct]);
        $productImage = ProductImage::create($data);

        if ($productImage) {
            return response()->json(['error' => false, 'message' => 'Product image created successfully', 'product_image' => $productImage], 201);
        }
        return response()->json(['error' => true, 'message' => 'Failed to create product image'], 500);
    }


    public function destroy(DeleteProductImageRequest $request, $productId, $imageId)
    {

        $request->validated();

        $productImage = ProductImage::where('product_id', $productId)->where('id', $imageId)->first();
        if (!$productImage) {
            return response()->json(['error' => true, 'message' => 'Product image not found'], 404);
        }
        $productImage->delete();

        return response()->json(['error' => false, 'message' => 'Product image deleted successfully'], 200);
    }
}
