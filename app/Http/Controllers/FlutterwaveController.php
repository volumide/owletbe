<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class FlutterwaveController extends Controller
{
    public function createPayment(Request $request){
        $client = new Client();
    
        $response = $client->post('https://api.flutterwave.com/v3/payments', [
            'headers' => [
                'Authorization' => 'Bearer FLWSECK-d79f6dafc5b4cf2567f20c4fa70060fa-1881fc42fe1vt-X',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'amount' => $request->amount,
                'currency' => 'NGN',
                'tx_ref' => $request->requestId,
                'redirect_url' =>$request->callback ?? 'http://localhost:5173/transaction',
                'customer' => [
                    "email" => $request->email,
                    "phonenumber"=> $request->phone,
                    "name" => $request->name
                    ]
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents());

        return response(["body" => $body, "status" => $statusCode]);
    }

        /**
     * create transaction on transaction table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transaction (Request $request)
    {
        $transaction = Transaction::create($request->all());
        return response(["status" => "success", "message" => "transaction created", "data" => $transaction], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTransaction(Request $request, $id)
    {
        
        $update = Transaction::where("transaction_id", $id)->update($request->all());
        return response(["status" => $update ? "success" : "fail", "message" => !$update ?"unable to update":"transaction updated" ], $update ? 200: 401);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactions(){
        return response(["status"=>"success", "data"=>Transaction::all()], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionById($id){
        $transaction = Transaction::find($id);
        return response(["status"=>"success", "data"=> $transaction], $transaction ? 200: 404);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionByUserId($id){
        $transaction = Transaction::firstWhere("user_id", $id);
        return response(["status"=>"success", "data"=>$transaction],  $transaction ? 200: 404);
    }

    public function topUpWallet(Request $request) {
        $userId = Auth::user();
        $wallet = Wallet::create($request->all());
        User::where("id", $userId->id)->update(["wallet_balance" => $userId->wallet_balance + $wallet->new_balance]);
        return response(["status"=>"success", "data"=>$wallet->id], 200);
    }

    public function getWallet(Request $request) {
        $userId = Auth::user();
        $wallet = Wallet::where("user_id", $userId->id);

        return response(["status"=>"success", "data"=>$userId->id], 200);
    }
}