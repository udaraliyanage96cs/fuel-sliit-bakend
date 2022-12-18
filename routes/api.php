<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\BowserController;
use App\Http\Controllers\FuelController;

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
    Route::post('/login',[UserController::class,'login_user']);
});

Route::prefix('station')->group(function () {
    Route::get('/{id?}',[StationController::class,'get_stations']);
    Route::post('/create',[StationController::class,'create_station']);
    Route::get('/delete/{id}',[StationController::class,'delete_station']);
    Route::post('/update/{id}',[StationController::class,'update_station']);
    Route::prefix('stocks')->group(function () {
        Route::get('/{id}',[StationController::class,'get_stocks']);
        Route::get('/specific/{id}',[StationController::class,'get_specific_stocks']);
        Route::get('/delete/{id}',[StationController::class,'delete_stocks']);
        Route::post('/update/{id}',[StationController::class,'update_stocks']);
    });
   
});

Route::prefix('fuel')->group(function () {
    Route::get('/{id?}',[FuelController::class,'get_fuel_type']);
    Route::post('/create',[FuelController::class,'create_fuel_type']);
    Route::get('/delete/{id}',[FuelController::class,'delete_fuel_type']);
    Route::post('/update/{id}',[FuelController::class,'update_fuel_type']);

    Route::prefix('capacity')->group(function () {
        Route::get('/dropdown',[FuelController::class,'get_capacity_fuel_type']);
        Route::post('/create',[FuelController::class,'create_fuel_capacity']);
    });

});

Route::prefix('bowser')->group(function () {
    Route::get('/{id?}',[BowserController::class,'get_bowser']);
    Route::get('/specific/{id?}',[BowserController::class,'get_bowser_specific']);
    Route::post('/create',[BowserController::class,'create_bowser']);
    Route::get('/delete/{id}',[BowserController::class,'delete_bowser']);
    Route::post('/update/{id}',[BowserController::class,'update_bowser']);
});