<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

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

// AUTH ENDPOINTS
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware(['auth:sanctum','auth:api'])->group(static function() {

    // CATEGORY RESOURCE ENDPOINTS
    Route::resource('categories', CategoryController::class);

    // MOVIE RESOURCE ENDPOINTS
    Route::post('/movies/{movie}/rate', [MovieController::class, 'rate'])->can('rate', 'movie');
    Route::patch('/movies/{movie}/rate', [MovieController::class, 'updateRate']);
    Route::resource('movies', MovieController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
