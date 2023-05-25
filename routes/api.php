<?php

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

Route::apiResource("user", UserController::class);
Route::post("login", [UserController::class, "login"]);
Route::post("transaction", [UserController::class, "transaction"]);
Route::put("transaction/{id}", [UserController::class, "updateTransaction"]);
Route::get("transactions", [UserController::class, "getTransactions"]);
Route::get("transaction/{id}", [UserController::class, "getTransactionById"]);
Route::get("transaction/user/{id}", [UserController::class, "getTransactionByUserId"]);
Route::get("payment", [FlutterwaveController::class, "createPayment"]);