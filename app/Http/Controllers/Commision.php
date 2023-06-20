<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Commision as ComModel;
use Illuminate\Support\Facades\Auth;

class Commision extends Controller
{
    public function new(Request $request){
        $comm = ComModel::create($request->all());
        return response(["status"=>"success","message" => "commisions updated"  ], 200); 
    }

    // public function update($id){
    //     $model = ComModel::where("primary" , true)->first();
    //     if($model){
    //         ComModel::where('id', $model->id)->update(["primary" => 0]);
    //     }
    //     $comm = ComModel::where('id',$id)->update(["primary"=> 1]);
    //     return response(["message"=>"success", "data"=>$comm ], 200); 

    // }

    // public function getDefault(){
    //     $comm = ComModel::where('primary', true)->first();
    //     return response(["message"=>"success", "data"=>$comm ], 200); 
    // }

    public function getAll(){
        $comm = ComModel::all();
        return response(["status"=>"success", "data"=>$comm ], 200); 
    }

    public function getLatest(){
        $commision = ComModel::latest()->first();
        return  response(["status" => "success", "message" => "transaction created", "data" => $commision ], 200);
    }
}