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
            return response()->json(['error'=>false,'message' => 'Category created successfully', 'category' => $category], 201);
        }
        return response()->json(['error' => true,'message' => 'Failed to create category'], 500);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        if ($category) {
            return response()->json(['error'=>false,'message' => 'Category updated successfully', 'category' => $category], 200);
        }
        return response()->json(['error' => true,'message' => 'Failed to update category'], 500);
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
            return response()->json(['error'=>false,'message' => 'Category deleted successfully', 'category' => $category], 200);
        }
        return response()->json(['error' => true,'message' => 'Failed to delete category'], 500);
    }
}
