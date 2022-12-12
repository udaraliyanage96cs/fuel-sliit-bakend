<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\BowserController;


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

Route::prefix('user')->group(function () {
    Route::get('/',[UserController::class,'get_users']);
    Route::post('/create',[UserController::class,'create_user']);
    Route::get('/delete/{id}',[UserController::class,'delete_users']);
    Route::post('/update/{id}',[UserController::class,'update_user']);
});

Route::prefix('station')->group(function () {
    Route::get('/{id?}',[StationController::class,'get_stations']);
    Route::post('/create',[StationController::class,'create_station']);
    Route::get('/delete/{id}',[StationController::class,'delete_station']);
    Route::post('/update/{id}',[StationController::class,'update_station']);
});

Route::prefix('bowser')->group(function () {
    Route::get('/{id?}',[BowserController::class,'get_bowser']);
    Route::post('/create',[BowserController::class,'create_bowser']);
    Route::get('/delete/{id}',[BowserController::class,'delete_bowser']);
    Route::post('/update/{id}',[BowserController::class,'update_bowser']);
});