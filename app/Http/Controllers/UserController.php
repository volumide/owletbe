<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(["message"=>"success", "data"=>User::all() ], 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate =Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'phone' => 'required|unique:users|min:11|max:11'
        ]);
        if($validate->fails()) return response(["status"=>"fail", "data"=>$validate->errors() ], 409);

        $password = bcrypt($request->password);
        $request['password'] = $password;
        $request['wallet_balance'] = 0;
        
        $user = User::create($request->all());
        return response(["status"=>"success","data" => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return response(["status" => "success", "data"=>$user ? $user :""], 200); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->email){
            $validate = Validator::make($request->all(), [
                'email' => 'unique:users|email',
            ]);
            if($validate->fails()) return response(["status"=>"fail", "data"=>$validate->errors() ], 409);
        }
        if($request->phone){
            $validate = Validator::make($request->all(), [
                'phone' => 'unique:users|min:11|max:11'
            ]);
            if($validate->fails()) return response(["status"=>"fail", "data"=>$validate->errors() ], 409);
        }
        $update = User::where("id", $id)->update($request->all());
        $user = Auth::user();
        return response(["status" => $update ? "success" : "fail", "message" => !$update ?"unable to update":"user updated", "data"=> $user ], $update ? 200: 401); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::find($id)->delete();
        return response(["status" => $delete ? "success" : "fail", "message" => !$delete ?"unable to delete":"user deleted" ], $delete ? 200: 401);
    }

    /**
     * login authenticated user from the storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login (Request $request)
    {
        $authenticate = auth()->attempt(["email" => $request->email, "password" => $request->password]);
        if($authenticate) {
            $user = auth()->user();
            // $useFind = User::find($user->id);
            $token = $user->createToken("owlet_token")->accessToken;
            return  response(["status" => "success", "message" => auth()->user(), "token" => $token], 200);
        } 
        return response(["status" => "fail", "message" => $authenticate], 401);
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if(!Hash::check($request->old_password, $user->password)){
            return response(["status" => "fail", "message" =>"password not matched" ], 401); 
        }
        $password = bcrypt($request->new_password);
        User::where("id", $user->id)->update(['password' => $password]);
        return response(["status" => "success", "message" =>"password succesully changed" ], 200); 
    }
}