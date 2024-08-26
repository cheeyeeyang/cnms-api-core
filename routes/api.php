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

Route::post('/login', [App\Http\Controllers\API\Auth\AuthApiController::class, 'login']);
Route::get('/test', [App\Http\Controllers\API\Auth\AuthApiController::class, 'get']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/forget_password', [App\Http\Controllers\API\Auth\AuthApiController::class, 'forget_password']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/get_dashboard', [App\Http\Controllers\API\OrderApiController::class, 'get_dashboard']);
    Route::post('/logout', [App\Http\Controllers\API\Auth\AuthApiController::class, 'logout']);
    Route::get('/get_profile', [App\Http\Controllers\API\Auth\AuthApiController::class, 'getProfile']);
    Route::post('/reset_forget_password', [App\Http\Controllers\API\Auth\AuthApiController::class, 'reset_forget_password']);

    Route::get('/alert', [App\Http\Controllers\API\AlertApiController::class, 'get']);
    Route::post('/alert', [App\Http\Controllers\API\AlertApiController::class, 'add']);
    Route::put('/alert/{id}', [App\Http\Controllers\API\AlertApiController::class, 'update']);
    Route::delete('/alert/{id}', [App\Http\Controllers\API\AlertApiController::class, 'delete']);
    //employee
    Route::get('/employee', [App\Http\Controllers\API\EmployeeApiController::class, 'get']);
    Route::post('/employee', [App\Http\Controllers\API\EmployeeApiController::class, 'add']);
    Route::post('/update/employee', [App\Http\Controllers\API\EmployeeApiController::class, 'update']);
    Route::post('/delete/employee', [App\Http\Controllers\API\EmployeeApiController::class, 'delete']);
    //zone
    Route::get('/zone', [App\Http\Controllers\API\ZoneApiController::class, 'get']);
    Route::post('/zone', [App\Http\Controllers\API\ZoneApiController::class, 'add']);
    Route::post('/update/zone', [App\Http\Controllers\API\ZoneApiController::class, 'update']);
    Route::post('/delete/zone', [App\Http\Controllers\API\ZoneApiController::class, 'delete']);
    //appointment
    Route::get('/appointment', [App\Http\Controllers\API\AppointmentController::class, 'get']);
    Route::post('/appointment', [App\Http\Controllers\API\AppointmentController::class, 'add']);
    Route::put('/appointment/{id}', [App\Http\Controllers\API\AppointmentController::class, 'update']);
    Route::delete('/appointment/{id}', [App\Http\Controllers\API\AppointmentController::class, 'delete']);
    //User
    Route::get('/get_user', [App\Http\Controllers\API\Auth\AuthApiController::class, 'get']);
    Route::post('/add_user', [App\Http\Controllers\API\Auth\AuthApiController::class, 'add']);
    Route::post('/update_user', [App\Http\Controllers\API\Auth\AuthApiController::class, 'update']);
    Route::post('/delete_user', [App\Http\Controllers\API\Auth\AuthApiController::class, 'delete']);

    //customer
    Route::get('/get_customer', [App\Http\Controllers\API\CustomerApiController::class, 'get']);
    Route::post('/add_customer', [App\Http\Controllers\API\CustomerApiController::class, 'add']);
    Route::post('/update_customer', [App\Http\Controllers\API\CustomerApiController::class, 'update']);
    Route::post('/delete_customer', [App\Http\Controllers\API\CustomerApiController::class, 'delete']);

    //product
    Route::get('/get_product', [App\Http\Controllers\API\ProductApiController::class, 'get']);
    Route::post('/add_product', [App\Http\Controllers\API\ProductApiController::class, 'add']);
    Route::post('/update_product', [App\Http\Controllers\API\ProductApiController::class, 'update']);
    Route::post('/delete_product', [App\Http\Controllers\API\ProductApiController::class, 'delete']);
    //plan
    Route::post('/add_plan', [App\Http\Controllers\API\PlanApiController::class, 'add_plan']);
    Route::get('/get_all_plan', [App\Http\Controllers\API\PlanApiController::class, 'get_all_plan']);
    Route::post('/add_not_plan', [App\Http\Controllers\API\NotplantApiController::class, 'add_not_plan']);
    Route::get('/get_plan_by_employee', [App\Http\Controllers\API\PlanApiController::class, 'get_plan_by_employee']);
    Route::get('/get_plan_detail_employee/{id}', [App\Http\Controllers\API\PlanApiController::class, 'get_plan_detail_employee']);
    // preorder
    Route::get('/get_preorder_total', [App\Http\Controllers\API\OrderApiController::class, 'get_preorder_total']);
    Route::post('/pre_order', [App\Http\Controllers\API\OrderApiController::class, 'preorder']);
    Route::get('/get_preorder', [App\Http\Controllers\API\OrderApiController::class, 'get_preorder']);
    Route::get('/get_preorder_detail/{id}', [App\Http\Controllers\API\OrderApiController::class, 'get_preorder_detail']);
    Route::get('/get_preorder_detail_by_customer/{id}', [App\Http\Controllers\API\OrderApiController::class, 'get_preorder_detail_by_customer']);
    Route::get('/get_preorder_by_employee', [App\Http\Controllers\API\OrderApiController::class, 'get_preorder_by_employee']);
    Route::get('/get_preorder_by_customer/{id}', [App\Http\Controllers\API\OrderApiController::class, 'get_preorder_by_customer']);
    Route::get('/delete_preorder/{id}', [App\Http\Controllers\API\OrderApiController::class, 'delete_preorder']);
    //unit
    Route::get('/unit', [App\Http\Controllers\API\UnitApiController::class, 'get']);
    Route::post('/unit', [App\Http\Controllers\API\UnitApiController::class, 'add']);
    Route::post('/update/unit', [App\Http\Controllers\API\UnitApiController::class, 'update']);
    Route::post('/delete/unit', [App\Http\Controllers\API\UnitApiController::class, 'delete']);
    //category
    Route::get('/category', [App\Http\Controllers\API\CategoryController::class, 'get']);
    Route::post('/category', [App\Http\Controllers\API\CategoryController::class, 'add']);
    Route::post('/update/category', [App\Http\Controllers\API\CategoryController::class, 'update']);
    Route::post('/delete/category', [App\Http\Controllers\API\CategoryController::class, 'delete']);
    // alert
    Route::post('/add_alert', [App\Http\Controllers\API\AlertApiController::class, 'add']);
    Route::post('/update_alert', [App\Http\Controllers\API\AlertApiController::class, 'update']);
    Route::get('/get_alert', [App\Http\Controllers\API\AlertApiController::class, 'get']);
    Route::post('/delete_alert', [App\Http\Controllers\API\AlertApiController::class, 'delete_alert']);
    Route::post('/confirm_alert_employee', [App\Http\Controllers\API\AlertApiController::class, 'confirm_alert_employee']);
    Route::get('/count_alert_employee', [App\Http\Controllers\API\AlertApiController::class, 'count_alert_employee']);
    Route::post('/edit_preorder_detail', [App\Http\Controllers\API\OrderApiController::class, 'edit_preorder_detail']);
    Route::post('/get_history_plan_by_employee', [App\Http\Controllers\API\PlanApiController::class, 'get_history_plan_by_employee']);
    Route::post('/get_history_plan_by_employee_by_date', [App\Http\Controllers\API\PlanApiController::class, 'get_history_plan_by_employee_by_date']);
    Route::get('/history_preorder_employee', [App\Http\Controllers\API\OrderApiController::class, 'history_preorder_employee']);
    Route::post('/history_preorder_employee_by_month', [App\Http\Controllers\API\OrderApiController::class, 'history_preorder_employee_by_month']);
    Route::get('/history_preorder_admin', [App\Http\Controllers\API\OrderApiController::class, 'history_preorder_admin']);
    Route::post('/history_preorder_admin_by_month', [App\Http\Controllers\API\OrderApiController::class, 'history_preorder_admin_by_month']);
    //target
    Route::post('/add_target', [App\Http\Controllers\API\TargetApiController::class, 'add']);
    Route::get('/get_target', [App\Http\Controllers\API\TargetApiController::class, 'get']);
    Route::get('/get_cal_target', [App\Http\Controllers\API\TargetApiController::class, 'getTarget']);
    //admin
    Route::get('/get_admin_preorder', [App\Http\Controllers\API\OrderApiController::class, 'get_admin_preorder']);
    Route::get('/get_admin_appointment', [App\Http\Controllers\API\OrderApiController::class, 'get_admin_appointment']);
    Route::post('/get_admin_appointment_by_month', [App\Http\Controllers\API\OrderApiController::class, 'get_admin_appointment_by_month']);
});
//alert