<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WorkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\UserController as AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', [\App\Http\Controllers\API\WorkCategoryController::class, 'index']);
Route::get('/tags', [\App\Http\Controllers\API\TagController::class, 'index']);
Route::group(['middleware' => 'user_auth'], function (){
    Route::group(['prefix' => 'user'], function (){
        Route::get('/get/data', [UserController::class, 'getData']);
        Route::post('/save/data', [UserController::class, 'saveData']);
    });

    Route::group(['prefix' => 'works'], function (){
        Route::post('/create', [WorkController::class, 'createWork']);
        Route::get('/get', [WorkController::class, 'getWorks']);
        Route::get('/statement/{work}', [WorkController::class, 'workStatement']);
    });
});

