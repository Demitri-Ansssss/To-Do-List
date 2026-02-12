<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json([
        'name' => $request->user()->name,
    ]);
})->middleware('auth:sanctum');

Route::get('/todos', [TodoController::class, 'index']);
Route::get('/todos/{id}', [TodoController::class, 'show'])->middleware('auth:sanctum');
Route::post('/todos', [TodoController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/todos/{id}', [TodoController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
