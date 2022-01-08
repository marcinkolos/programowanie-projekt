<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('users', [UserController::class, 'getUsers']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('posts', [PostController::class, 'addPost']);
    Route::get('post/{id}', [PostController::class, 'getPost']);
    Route::get('posts', [PostController::class, 'getPosts']);
    Route::delete('post/{id}', [PostController::class, 'deletePost']);
    Route::patch('post/{id}', [PostController::class, 'patchPost']);
});