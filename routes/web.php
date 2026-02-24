<?php

use App\Http\Controllers\viewApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [viewApiController::class, 'index']);