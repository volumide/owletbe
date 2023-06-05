<?php

use App\Http\Controllers\Commision;
use App\Http\Controllers\FlutterwaveController;
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

Route::group(['middleware' => 'auth:api'], function() {
	Route::apiResource("user", UserController::class)->except("store");
	Route::post("transaction", [FlutterwaveController::class, "transaction"]);
	Route::post("transactions", [FlutterwaveController::class, "getTransactions"]);
	Route::put("transaction/{id}", [FlutterwaveController::class, "updateTransaction"]);
	Route::get("transactions", [FlutterwaveController::class, "getTransactions"]);
	Route::get("transaction/{id}", [FlutterwaveController::class, "getTransactionById"]);
	Route::get("transactions/user", [FlutterwaveController::class, "userTransaction"]);
	Route::post("payment", [FlutterwaveController::class, "createPayment"]);
	Route::get("wallet", [FlutterwaveController::class, "getWallet"]);
	Route::post("top/up", [FlutterwaveController::class, "topUpWallet"]);
	
	Route::get("commision", [Commision::class, "getDefault"]);
	Route::get("commision/{id}", [Commision::class, "getDefault"]);
	Route::put("commision/{id}", [Commision::class, "update"]);
	Route::get("commisions", [Commision::class, "getAll"]);
	Route::post("commision", [Commision::class, "new"]);
});