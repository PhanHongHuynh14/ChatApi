<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZoomController;
// use App\Http\Controllers\Api\ParticipantController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::controller(UserController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::delete('user/{id}', 'destroy');
    Route::get('users', 'getAlluser');
    Route::get('token', 'getToken');
    Route::post('zoom', 'createZoom');
    Route::get('user/{id}', 'getUser');

})->middleware('auth:sanctum');

Route::controller(ZoomController::class)->group(function(){
    Route::post('zoom', 'createZoom');
    Route::get('zoom', 'getZoomAll');
    Route::get('zoom/{zoom_id}', 'getZoom');
    Route::delete('zoom/{zoom_id}', 'deleteZoom');
});

Route::post('/zoom/{zoom_id}/users', [App\Http\Controllers\Api\ParticipantController::class, 'store']);
Route::get('/zoom/{zoom_id}/users', [App\Http\Controllers\Api\ParticipantController::class, 'index']);

Route::post('/zoom/{zoom_id}/{user_id}/messages', [App\Http\Controllers\Api\MessageController::class, 'store']);
Route::get('/zoom/{zoom_id}/{user_id}/messages', [App\Http\Controllers\Api\MessageController::class, 'index']);
Route::get('/zoom/{zoom_id}/messages', [App\Http\Controllers\Api\MessageController::class, 'getZoom']);



