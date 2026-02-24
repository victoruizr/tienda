<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
Route::apiResource('products', \App\Http\Controllers\ProductController::class);

Route::post('products/{product}/images', [\App\Http\Controllers\ProductImageController::class, 'store']);
Route::delete('products/{product}/images/{imageId}', [\App\Http\Controllers\ProductImageController::class, 'destroy']);
