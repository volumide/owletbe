<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class FlutterwaveController extends Controller
{
    public function createPayment(Request $request){
        $client = new Client();
        $timeStamp= now()->format('YmdHis');
        $uid = Str::uuid()->toString();
        // $response = $client->post('https://api.flutterwave.com/v3/payments', [
        $response = $client->post('https://api.paystack.co/transaction/initialize', [
            'headers' => [
                'Authorization' => 'Bearer '. env("FLUTTER_KEY"),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'amount' => $request->amount,
                'currency' => 'NGN',
                'tx_ref' => $timeStamp . '-' . $uid,
                'redirect_url' =>$request->callback ?? 'http://localhost:5173/transaction',
                'callback_url'=>$request->callback ?? 'http://localhost:5173/transaction',

                "email" => $request->email,
                "phonenumber"=> $request->phone,
                "name" => $request->name,
                
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

    public function verifyPayment(Request $request){
        $client = new Client();
        $response = $client->get("https://api.paystack.co/transaction/verify/{$request->id}", [
            'headers' => [
                'Authorization' => "Bearer ".  env("FLUTTER_KEY"),
                'Accept' => 'application/json',
            ],
        ]);
        $statusCode = $response->getStatusCode();
        $responseData = json_decode($response->getBody()->getContents(), true);

        return response(["body" => $responseData, "status" => $statusCode]);

    }

        /**
     * create transaction on transaction table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transaction (Request $request)
    {
        $count = Transaction::count();
        $count += 1;
        $transId = str_pad($count, 4, '0', STR_PAD_LEFT);
        $transaction = Transaction::create([
            "user_id" =>  $request->id,
            "type"=>$request->reason,
            "requestId" => $request->requestId,
            "transaction_id"=>  $transId,
            "phone" => $request->phone,
            "amount"=>$request->amount,
            "tx_ref"=> $request->ref,
            "data" => $request->data
        ]);
        // $transaction = Transaction::create($request->all());
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
        $user = Auth::user();
        $transaction = Transaction::all();
        return response(["status"=>"success", "data"=>Transaction::all(), "user"=> $user], 200);
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


    public function userTransaction() {
        $user = Auth::user();
        $transaction = Transaction::where("user_id", $user->id)->get();
        $latest =Transaction::latest()->take(10)->where("user_id", $user->id)->get();
        return response(["status"=>"success", "id"=> $user->id, "data"=>$transaction, "latest"=>$latest],  $transaction ? 200: 404);
    }

    public function topUpWallet(Request $request) {
        $userId = Auth::user();
        $request['user_id'] = $userId->id;
        $request['old_balance'] = $userId->wallet_balance ?? "0";
        $request['type'] = "topup";
        $request['new_balance'] = $request->amount;
        $wallet = Wallet::create($request->all());

        User::where("id", $userId->id)->update(["wallet_balance" => $userId->wallet_balance + $wallet->new_balance]);
        $count = Transaction::count();
        $count += 1;
        $uid = Str::uuid()->toString();
        $tranId = str_pad($count,4, '0', STR_PAD_LEFT);
        Transaction::create([
            "user_id" =>  $userId->id,
            "type"=>"wallet topup",
            // "requestId" => $request->requestId,
            "transaction_id"=> $tranId ,
            "phone" => $userId->phone,
            "amount"=>$request->amount,
            "tx_ref"=> $tranId . '-' . $uid,
        ]);
        
        return response(["status"=>"success", "data"=>$wallet->id], 200);
    }

    public function payWithWallet(Request $request){
        $userId = Auth::user();
        $request['user_id'] = $userId->id;
        $request['old_balance'] = $userId->wallet_balance ?? "0";
        $request['type'] = "withdraw";
        $request['new_balance'] = $request->amount;
        $wallet = Wallet::create($request->all());

        if($userId->wallet_balance < $request->amount){{
            return response(["status"=>"success", "message"=>"insufficent wallet balance"], 405);
        }}
        
        $count = Transaction::count();
        $count += 1;
        $uid = Str::uuid()->toString();
        $tranId = str_pad($count,4, '0', STR_PAD_LEFT);

        User::where("id", $userId->id)->update(["wallet_balance" => $userId->wallet_balance - $wallet->new_balance]);
        $uid = Str::uuid()->toString();
        
        Transaction::create([
            "user_id" =>  $userId->id,
            "type"=>$request->reason,
            "requestId"=>$request->requestId,
            "transaction_id"=>  $tranId,
            "phone" => $request->phone,
            "amount"=>$request->amount,
            "tx_ref"=> $tranId . '-' . $uid,
            "data" =>  $request->data
        ]);
        
        return response(["status"=>"success", "data"=>$wallet->id], 200);
    }

    public function getWallet(Request $request) {
        $userId = Auth::user();
        $wallet = Wallet::where("user_id", $userId->id);

        return response(["status"=>"success", "data"=>$userId->id, "user"=> $userId ], 200);
    }
}