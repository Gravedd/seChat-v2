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

    /**
     * Роуты изменений профиля пользователя
     * url: /users/
     */
    Route::patch('/users/', [\App\Http\Controllers\UsersController::class, 'changeSomething']);
    Route::get('/friends', [\App\Http\Controllers\FriendsController::class, 'friendlistapproved']);
    Route::get('/friends/unproved', [\App\Http\Controllers\FriendsController::class, 'friendlistunproved']);

    /** Запрос в друзья(запрос, подтверждение) */
    Route::post('/friends/{friend_id}', [\App\Http\Controllers\FriendsController::class, 'friendrequest']);
    /** Удаление из друзей(запрос, подтверждение) */
    Route::delete('friends/{friend_id}', [\App\Http\Controllers\FriendsController::class, 'deletefriend']);


    /** СООБЩЕНИЯ */
//    Route::get('/dialogues/{friend_id}', [\App\Http\Controllers\MessagesController::class, 'getUserMessagesInDialog']);

    /** Диалоги */
/*    Route::get('/dialogues/', [\App\Http\Controllers\DialoguesController::class, 'getDialogues']);*/
});
/**
 * РОУТЫ АВТОРИЗАЦИИ
 * URL: .../api/...
 */
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
