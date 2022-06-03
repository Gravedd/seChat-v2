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

/**
 * ROUTE GROUPS WITH AUTHORIZATION CHECK
 */
Route::group(['middleware' => 'auth:sanctum'], function() {

    /**
     * Find users by name with pagination
     * url: /users?page={page num}&q={username}
    */
    Route::get('/users', [\App\Http\Controllers\UsersController::class, 'findUsers']);

    /**
     * Get user information by id
     * url: /users/{id}
    */
    Route::get('/users/{userid}', [\App\Http\Controllers\UsersController::class, 'getUser']);

    /**
     * User Profile Change Route
     * url: /users/
     */
    Route::patch('/users/', [\App\Http\Controllers\UsersController::class, 'changeSomething']);

    /**
     * Friends routes
     * url: /friends/
     */
    //Get user friends
    Route::get('/friends', [\App\Http\Controllers\FriendsController::class, 'friendlistapproved']);
    //Get user unproved friends
    Route::get('/friends/unproved', [\App\Http\Controllers\FriendsController::class, 'friendlistunproved']);
    //Create friend request
    Route::post('/friends/{friend_id}', [\App\Http\Controllers\FriendsController::class, 'friendrequest']);
    //Remove from friends
    Route::delete('friends/{friend_id}', [\App\Http\Controllers\FriendsController::class, 'deletefriend']);
});
/**
 * AUTHORIZATION ROUTES
 * URL: .../api/...
 */
//register a user
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
//login a user
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
//logout a user
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');

/**
 * Get user information by token
 * url: /checkauth
 */
Route::middleware('auth:sanctum')->get('/checkauth', function (Request $request) {
    return $request->user();
});
