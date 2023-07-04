<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function saveService(Request $request)
    {
        Service::create($request->all());
        return  response(["status" => "success", "message" => "transaction created"], 200);
    }

    public function getLatest()
    {
        $service = Service::latest()->first();
        return  response(["status" => "success", "message" => "transaction created", "data" => $service ], 200);
    }

    public function getAllService()
    {
        $services = Service::all();
        return  response(["status" => "success", "message" => "transaction created", "data" => $services ], 200);
    }

    public function checkAmount(Request $request){
        if($request->quantity && $request->quantity < 1){
            return response(["status"=>"failed", "message"=> "insufficient balance"], 405);
        }
        
        if($request->amount && $request->amount < 1){
            return response(["status"=>"failed", "message"=> "insufficient balance"], 405);
        }
        
        return response(["status"=>"success", "message"=> "successs"], 200);
    }
}