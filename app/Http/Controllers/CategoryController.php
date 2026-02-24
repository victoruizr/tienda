<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {

        $data = array_merge($request->validated(), ['product_id' => $request->route('product')]);
        $category = Category::create($data);
        if ($category) {
            return response()->json(['error'=>false,'message' => 'Categoría creada con éxito', 'category' => $category], 201);
        }
        return response()->json(['error' => true,'message' => 'No se pudo crear la categoría.'], 500);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        if ($category) {
            return response()->json(['error'=>false,'message' => 'Categoría actualizada correctamente', 'category' => $category], 200);
        }
        return response()->json(['error' => true,'message' => 'No se pudo actualizar la categoría.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCategoryRequest $request, $id)
    {
        $request->validated();

        $category = Category::findOrFail($id);
        $category->delete();

        if ($category) {
            return response()->json(['error'=>false,'message' => 'Categoría eliminada con éxito', 'category' => $category], 200);
        }
        return response()->json(['error' => true,'message' => 'No se pudo eliminar la categoría.'], 500);
    }
}
