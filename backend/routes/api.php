<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/checkauth', function (Request $request) {
    return $request->user();
});


/**
 * ГРУППЫ РОУТОВ С ПРОВЕРКОЙ АВТОРИЗАЦИИ
 */
Route::group(['middleware' => 'auth:sanctum'], function() {
    /**
     * Получить список пользователей
     * url: /users?page={номер страницы}&q={имя искомого}
    */
    Route::get('/users', [\App\Http\Controllers\UsersController::class, 'findUsers']);

    /**
     * Получить пользователя по id
     * url: /users/{id}
    */
    Route::get('/users/{userid}', [\App\Http\Controllers\UsersController::class, 'getUser']);
});


/**
 * РОУТЫ АВТОРИЗАЦИИ
 * URL: .../api/...
 */
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
