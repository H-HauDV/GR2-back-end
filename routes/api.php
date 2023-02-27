<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\TransactionController;


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

//Auth
Route::get('token', function (Request $request) {
    $token = $request->session()->token();
    $token = csrf_token();
    return Response()->json(array("token" => $token));
});
Route::post('/users/login', [UserController::class, 'onLogin'])->name('user.login');
Route::post('/users/register', [UserController::class, 'onRegister'])->name('user.register');
Route::get('/users-get/balance/', [UserController::class, 'getBalanceOfId'])->name('user.getBalanceOfId');
Route::get('/users-get/infor/', [UserController::class, 'getInformation'])->name('user.getInformation');
Route::post('/users-post/avatar/', [UserController::class, 'updateLogo'])->name('user.updateLogo');
Route::get('/users-get/avatar/', [UserController::class, 'getLogo'])->name('user.getLogo');


//Coupon
Route::get('/coupons-get/of-user/', [CouponController::class, 'getCouponsOfUser'])->name('coupon.getCouponsOfUser');
Route::post('/coupons-post/create/', [CouponController::class, 'createCoupon'])->name('coupon.createCoupon');

//Transaction
Route::post('/transaction-post/create/', [TransactionController::class, 'createTransaction'])->name('transaction.createTransaction');


