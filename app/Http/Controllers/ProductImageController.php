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
            return response()->json(['error' => false, 'message' => 'Imagen del producto creada correctamente', 'product_image' => $productImage], 201);
        }
        return response()->json(['error' => true, 'message' => 'No se pudo crear la imagen del producto.'], 500);
    }


    public function destroy(DeleteProductImageRequest $request, $productId, $imageId)
    {

        $request->validated();

        $productImage = ProductImage::where('product_id', $productId)->where('id', $imageId)->first();
        if (!$productImage) {
            return response()->json(['error' => true, 'message' => 'Imagen del producto no encontrada'], 404);
        }
        $productImage->delete();

        return response()->json(['error' => false, 'message' => 'Imagen del producto eliminada con éxito'], 200);
    }
}
