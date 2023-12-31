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
Route::post('/login',[App\Http\Controllers\API\Auth\AuthApiController::class, 'login']);
Route::post('/logout',[App\Http\Controllers\API\Auth\AuthApiController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/get_profile',[App\Http\Controllers\API\Auth\AuthApiController::class, 'getProfile']);
    Route::get('/alert',[App\Http\Controllers\API\AlertApiController::class, 'get']);
    Route::post('/alert',[App\Http\Controllers\API\AlertApiController::class, 'add']);
    Route::put('/alert/{id}',[App\Http\Controllers\API\AlertApiController::class, 'update']);
    Route::delete('/alert/{id}',[App\Http\Controllers\API\AlertApiController::class, 'delete']);
    //employee
    Route::get('/employee',[App\Http\Controllers\API\EmployeeApiController::class, 'get']);
    Route::post('/employee',[App\Http\Controllers\API\EmployeeApiController::class, 'add']);
    Route::post('/update/employee',[App\Http\Controllers\API\EmployeeApiController::class, 'update']);
    Route::post('/delete/employee',[App\Http\Controllers\API\EmployeeApiController::class, 'delete']);
    //zone
    Route::get('/zone',[App\Http\Controllers\API\ZoneApiController::class, 'get']);
    Route::post('/zone',[App\Http\Controllers\API\ZoneApiController::class, 'add']);
    Route::post('/update/zone',[App\Http\Controllers\API\ZoneApiController::class, 'update']);
    Route::post('/delete/zone',[App\Http\Controllers\API\ZoneApiController::class, 'delete']);
    //appointment
    Route::get('/appointment',[App\Http\Controllers\API\AppointmentApiController::class, 'get']);
    Route::post('/appointment',[App\Http\Controllers\API\AppointmentApiController::class, 'add']);
    Route::put('/appointment/{id}',[App\Http\Controllers\API\AppointmentApiController::class, 'update']);
    Route::delete('/appointment/{id}',[App\Http\Controllers\API\AppointmentApiController::class, 'delete']);
    //User
    Route::get('/get_user',[App\Http\Controllers\API\Auth\AuthApiController::class, 'get']);
    Route::post('/add_user',[App\Http\Controllers\API\Auth\AuthApiController::class, 'add']);
    Route::post('/update_user',[App\Http\Controllers\API\Auth\AuthApiController::class, 'update']);
    Route::post('/delete_user',[App\Http\Controllers\API\Auth\AuthApiController::class, 'delete']);

    //customer
    Route::get('/get_customer',[App\Http\Controllers\API\CustomerApiController::class, 'get']);
    Route::post('/add_customer',[App\Http\Controllers\API\CustomerApiController::class, 'add']);
    Route::post('/update_customer',[App\Http\Controllers\API\CustomerApiController::class, 'update']);
    Route::post('/delete_customer',[App\Http\Controllers\API\CustomerApiController::class, 'delete']);

    //product
    Route::get('/get_product',[App\Http\Controllers\API\ProductApiController::class, 'get']);
    Route::post('/add_product',[App\Http\Controllers\API\ProductApiController::class, 'add']);
    Route::post('/update_product',[App\Http\Controllers\API\ProductApiController::class, 'update']);
    Route::post('/delete_product',[App\Http\Controllers\API\ProductApiController::class, 'delete']);
});
//alert