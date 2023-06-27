<?php

use App\Http\Controllers\Commision;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get("/", function(){
//     return "working";
// });


Route::post("login", [UserController::class, "login"]);
Route::post("user", [UserController::class, "store"]);
Route::post("reset", [UserController::class, "resetPassword"]);
Route::post("payment", [FlutterwaveController::class, "createPayment"]);
Route::post("transaction", [FlutterwaveController::class, "transaction"]);
Route::put("transaction/{id}", [FlutterwaveController::class, "updateTransaction"]);

Route::post("payment/verify", [FlutterwaveController::class, "verifyPayment"]);
Route::post("send/mail", [UserController::class, "sendMail"]);

Route::get("service/latest", [ServiceController::class, "getLatest"]);
Route::get("service/all", [ServiceController::class, "getAllService"]);
Route::post("send/recipt", [UserController::class, "review"]);
Route::get("commision", [Commision::class, "getLatest"]);
Route::group(['middleware' => 'auth:api'], function() {
	Route::apiResource("user", UserController::class)->except("store");
	Route::put("change/password", [UserController::class, "updatePassword"]);

	// Route::post("transactions", [FlutterwaveController::class, "transaction"]);
	
	Route::get("transactions", [FlutterwaveController::class, "getTransactions"]);
	Route::get("transaction/{id}", [FlutterwaveController::class, "getTransactionById"]);
	Route::get("transactions/user", [FlutterwaveController::class, "userTransaction"]);
	
	Route::get("wallet", [FlutterwaveController::class, "getWallet"]);
	Route::post("top/up", [FlutterwaveController::class, "topUpWallet"]);
	Route::post("withdraw", [FlutterwaveController::class, "payWithWallet"]);
	
	Route::put("commision/{id}", [Commision::class, "update"]);
	Route::get("commisions", [Commision::class, "getAll"]);
	Route::post("commision", [Commision::class, "new"]);

	Route::post("service", [ServiceController::class, "saveService"]);

});