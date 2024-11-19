<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\NewsPostController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('refresh-token', [AuthenticationController::class, 'refreshToken']);


Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);
    Route::get('user', [AuthenticationController::class, 'loginUser']);

    Route::post('/create-category', [NewsPostController::class, 'submitCategory']);
    Route::get('/list-categories', [NewsPostController::class, 'listCategories']);
});



// Route::middleware(['check.jwt.expiration'])->group(function () {
//     Route::post('logout', [AuthenticationController::class, 'logout']);
//     Route::get('user', [AuthenticationController::class, 'loginUser']);
//     Route::post('/create-category', [NewsPostController::class, 'submitCategory']);
// });
