<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\BowserController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\AuditController;

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
    Route::get('/list',[UserController::class,'get_users_list']);
    Route::post('/create',[UserController::class,'create_user']);
    Route::get('/delete/{id}',[UserController::class,'delete_users']);
    Route::post('/update/{id}',[UserController::class,'update_user']);
    Route::post('/login',[UserController::class,'login_user']);
    
    
    Route::prefix('vehicle')->group(function () {
        Route::get('/{id?}',[UserController::class,'get_vehicle']);
        Route::post('/create',[UserController::class,'create_vehicle']);
        Route::get('/delete/{id}',[UserController::class,'delete_vehicle']);
        Route::post('/update/{id}',[UserController::class,'update_vehicle']);
        
        Route::get('/sp/{id?}',[UserController::class,'get_vehicle_sp']);
        Route::get('/joinqueue/{id?}/{sid}',[UserController::class,'get_joinqueue']);
        Route::get('/getqueue/{id?}',[UserController::class,'get_getqueue']);
        Route::get('/leftqueue/{id?}',[UserController::class,'delete_joinqueue']);
        Route::post('/joinqueue',[UserController::class,'create_joinqueue']);
    });
    
});

Route::prefix('station')->group(function () {
    Route::get('/{id?}',[StationController::class,'get_stations']);
    Route::post('/create',[StationController::class,'create_station']);
    Route::get('/delete/{id}',[StationController::class,'delete_station']);
    Route::post('/update/{id}',[StationController::class,'update_station']);
    // Route::get('/stocks/{id}',[StationController::class,'get_stocks']);
    // Route::get('/stocks/specific/{id}',[StationController::class,'get_specific_stocks']);
    // Route::get('/stocks/delete/{id}',[StationController::class,'delete_stocks']);
    
    Route::prefix('stocks')->group(function () {
        Route::get('/{id}',[StationController::class,'get_stocks']);
        Route::get('/specific/{id}',[StationController::class,'get_specific_stocks']);
        Route::get('/delete/{id}',[StationController::class,'delete_stocks']);
        Route::post('/update/{id}',[StationController::class,'update_stocks']);
    });

    Route::prefix('queue')->group(function () {
        Route::get('/{id}',[StationController::class,'get_queue']);
        Route::get('/count/{id}',[StationController::class,'get_queue_count']);
        Route::get('/category/{id}',[StationController::class,'get_category_queue']);
        Route::get('/delete/{id}',[StationController::class,'delete_queue']);
        Route::post('/update/{id}',[StationController::class,'update_queue']);
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
    // Route::get('/{id?}',[BowserController::class,'get_bowser']);
    // Route::post('/create',[BowserController::class,'create_bowser']);
    // Route::get('/delete/{id}',[BowserController::class,'delete_bowser']);
    // Route::post('/update/{id}',[BowserController::class,'update_bowser']);
    
    Route::get('/{id?}',[BowserController::class,'get_bowser']);
    Route::get('/home/{id?}',[BowserController::class,'get_bowser_home']);
    Route::get('/specific/{id?}',[BowserController::class,'get_bowser_specific']);
    Route::post('/create',[BowserController::class,'create_bowser']);
    Route::get('/delete/{id}',[BowserController::class,'delete_bowser']);
    Route::post('/update/{id}',[BowserController::class,'update_bowser']);

    
    
});

Route::prefix('audit')->group(function () {
    
    Route::get('/{id}/{role}',[AuditController::class,'get_audit']);
    Route::post('/create',[AuditController::class,'create_audit']);
    Route::get('/delete/{id}',[AuditController::class,'delete_audit']);
    Route::post('/update/{id}',[AuditController::class,'update_audit']);
    
});
