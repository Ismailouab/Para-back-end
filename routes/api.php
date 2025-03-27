<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('register', [AuthController::class, 'register']); // Register a new user
Route::post('login', [AuthController::class, 'login'])->name('login'); // Login
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']); // Logout

// User Routes (admin-only or authorized)
Route::middleware('auth:sanctum')->get('users', [UserController::class, 'index']); // Get all users
Route::middleware('auth:sanctum')->get('users/{id}', [UserController::class, 'show']); // Get a specific user
Route::middleware('auth:sanctum')->post('users', [UserController::class, 'store']); // Create a new user
Route::middleware('auth:sanctum')->put('users/{id}', [UserController::class, 'update']); // Update a user
Route::middleware('auth:sanctum')->delete('users/{id}', [UserController::class, 'destroy']); // Delete a user

// Category Routes    
Route::get('categories', [CategoryController::class, 'index']); // List all categories
Route::get('categories/{id}', [CategoryController::class, 'show']); // Show a specific category
Route::middleware('auth:sanctum')->group(function () {
    Route::post('users/{id}/categories', [CategoryController::class, 'store']); // Create a category for a user
    Route::put('users/{id}/categories/{category}', [CategoryController::class, 'update']); // Update a user's category
    Route::delete('users/{id}/categories/{category}', [CategoryController::class, 'destroy']); // Delete a user's category
});

// product Routes
Route::get('products', [ProductController::class, 'index']); // Get all products
Route::get('products/{id}', [ProductController::class, 'show']); // Get a specific product item

Route::middleware('auth:sanctum')->group(function () {
    Route::post('users/{id}/products', [ProductController::class, 'store']); // Create a new product item by admin
    Route::put('users/{id}/products/{product}', [ProductController::class, 'update']); // Update a product item by admin
    Route::delete('users/{id}/products/{product}', [ProductController::class, 'destroy']); // Delete a food item by admin
});
// Order Routes
Route::get('orders', [OrderController::class, 'index']); // Get all orders
Route::get('orders/{id}', [OrderController::class, 'show']); // Get a specific order
Route::middleware('auth:sanctum')->get('/users/{userId}/orders', [OrderController::class, 'getOrdersByUserId']); // Get orders by user ID
Route::middleware('auth:sanctum')->post('/users/{userId}/orders', [OrderController::class, 'store']); // Create a new order
Route::middleware('auth:sanctum')->put('/users/{userId}/orders/{id}', [OrderController::class, 'update']); // Update an order
Route::middleware('auth:sanctum')->delete('/users/{userId}/orders/{id}', [OrderController::class, 'destroy']); // Delete an order

// Get authenticated user (for testing purposes)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
