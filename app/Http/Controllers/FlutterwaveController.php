<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FlutterwaveController extends Controller
{
    public function createPayment(){
        $client = new Client();
    
        $response = $client->post('https://api.flutterwave.com/v3/payments', [
            'headers' => [
                'Authorization' => 'Bearer FLWSECK-d79f6dafc5b4cf2567f20c4fa70060fa-1881fc42fe1vt-X',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'amount' => 10,
                'currency' => 'NGN',
                'tx_ref' => date("Y-m-d H:i:s"),
                'redirect_url' => 'http://example.com/callback',
            
                'customer' => [
                    'email' => "user@gmail.com",
                    'phonenumber' => "080****4528",
                    'name' => "Yemi Desola"
                ],
                // Additional request parameters as needed
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents());

        return response(["body" => $body, "status" => $statusCode]);
    }
}