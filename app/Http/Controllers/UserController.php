<?php

namespace App\Http\Controllers;


use App\Mail\Verification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    protected $mailingService;
    public function __construct(Verification $verify) {
        $this->mailingService = $verify;
    }
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
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $validate =Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'phone' => 'required|unique:users|min:11|max:11'
        ]);
        if($validate->fails()) return response(["status"=>"fail", "data"=>$validate->errors() ], 409);
        $data = [
            'message' => "",
            "name" => $request->first_name. " " . $request->last_name
        ];
        $htmlContent = View::make("verification", $data)->render();
        $password = bcrypt($request->password);
        // $request['verification_code'] = $code;
        $request['password'] = $password;
        $request['wallet_balance'] = 0;
        $response = $this->mailingService->sendMail($request->email, "Welcome Mail", $htmlContent);
        $user = User::create($request->all());
        return response(["status"=>"success","data" => $user, $response], 201);
    }

    // public function verifyMail(Request $request) {
    //      $user = User::where(['email' => $request->email, 'verification_code' => $request->code ]);
    //      if(!$user){
    //         return response(["status"=>"success","message" => "verifed"], 409);
    //      }
    //      return response(["status"=>"success","message" => "verifed"], 201);
    // }

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
        $request['temporal'] = "false";
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

    public function sendMail (Request $request) 
    {
        

        $user= "volumide42@gmail.com";
        $response = $this->mailingService->sendMail($request->to, $request->subject, $request->content);
        return $response;
        // dd($response);
        // if ($response->success_count > 0) {
        //     dd($response);
        // } else {
        //     // Error occurred while sending email
        // }
        // $users = Mail::to($user)->send(new Verification());
        // dd($users);
    }

    // public function forgotPaswordCode(Request $request) {
    //     $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    //     $user = User::where("email", $request->email)->first();
    //     if(!$user){
    //         return response(["status" => "fail", "message" =>"user not found" ], 404); 
    //     }

    //     User::where("email", $request->email)->update("code", $code);
    //     return response(["status" => "success", "message" =>"user not found", "data" => $code ], 200); 
    // }

    public function resetPassword(Request $request)
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $password = bcrypt($code);
        $user = User::where([
            "email"=> $request->email,
        ])->first();
        if(!$user){
            return response(["status" => "fail", "message" =>"Unknown user" ], 404); 
        }
        $data = [
            'password' => $code,
            'message' => 'This is a temporal password',
        ];
        // Compile the Blade template into HTML and pass data
        $htmlContent = View::make("email", $data)->render();
        $response = $this->mailingService->sendMail($request->to, "Forgot Password", $htmlContent, "no-reply@owletpay.com");
        $user = User::where([
            "email"=> $request->email,
        ])->update(["password" => $password,  "temporal"=>"true"]);
        return response(["status" => "success", "message" => "password reset success", "new_password"=> $code, "response"=> $response  ], 200); 
    }

    public function review() 
    {
        $data = [
            "code" => "000",
            "content" => [
                "transactions" => [
                    "amount" => 1000,
                    "convinience_fee" => 0,
                    "status" => "delivered",
                    "name" => null,
                    "phone" => "07061933309",
                    "email" => "sandbox@vtpass.com",
                    "type" => "Electricity Bill",
                    "created_at" => "2019-08-17 02:27:26",
                    "discount" => null,
                    "giftcard_id" => null,
                    "total_amount" => 992,
                    "commission" => 8,
                    "channel" => "api",
                    "platform" => "api",
                    "service_verification" => null,
                    "quantity" => 1,
                    "unit_price" => 1000,
                    "unique_element" => "1010101010101",
                    "product_name" => "Eko Electric Payment - EKEDC",
                ],
            ],
            "response_description" => "TRANSACTION SUCCESSFUL",
            "requestId" => "hg3hgh3gdiud4w2wb33",
            "amount" => "1000.00",
            "transaction_date" => [
                "date" => "2019-08-17 02:27:27.000000",
                "timezone_type" => 3,
                "timezone" => "Africa/Lagos",
            ],
            "purchased_code" => "Token : 42167939781206619049 Bonus Token : 62881559799402440206",
            "mainToken" => "42167939781206619049",
            "mainTokenDescription" => "Normal Sale",
            "mainTokenUnits" => 16666.666,
            "mainTokenTax" => 442.11,
            "mainsTokenAmount" => 3157.89,
            "bonusToken" => "62881559799402440206",
            "bonusTokenDescription" => "FBE Token",
            "bonusTokenUnits" => 50,
            "bonusTokenTax" => null,
            "bonusTokenAmount" => null,
            "tariffIndex" => "52",
            "debtDescription" => "1122",
        ];
        return view("/test", $data);
    }
}