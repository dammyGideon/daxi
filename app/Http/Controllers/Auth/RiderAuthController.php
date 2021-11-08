<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Use App\Models\User;
use App\Helpers\MyPaystack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Validator;
use App\Models\Wallet;
use App\Helpers\OneTimePassword;
use App\Models\savedToken;
use App\Models\onlineRiders;

class RiderAuthController extends Controller
{
    //

    public function register(Request $request){

        $password=bcrypt($request->phone);
        $email=$request->email;
        $vehicle=$request->vehicle;
        $phone=$request->phone;
        $role='rider';

        $OTp = User::where('email',$email)->value('otp');
        if(empty($OTp)){
            $nullOTp = User::where('email',$email)->value('email');
            if(!empty($nullOTp)){
                $nullOTp = savedToken::where('email',$email)->value('token');
                //otp class
               $otp= new OneTimePassword();
               $otp->sendOtp($phone);

                return response()->json([
                    'status'=>true,
                    'message'=>'registration successful',
                    "token"=>$nullOTp
                ], 200);
            }else{

                 $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users',
                    'phone' => 'required|unique:users',
                    'vehicle' => 'required',
                ]);
                if($validator->fails()){
                    return response([
                        'error' =>$validator->errors()->all()
                    ], 422);
                }

                $user = new User();

                $user->email       =$email;
                $user->password    =$password;
                $user->vehicle_type =$vehicle;
                $user->phone       =$phone;
                $user->role        =$role;
                $user->save();

                $otp= new OneTimePassword();
                $otp->sendOtp($phone);


                $token = $user->createToken('token')->accessToken;

                $saveToken = new savedToken();
                $saveToken->token=$token;
                $saveToken->email=$email;
                $saveToken->save();

                $onlineriders =new onlineRiders();
                $onlineriders->rider_id = $user->id;
                $onlineriders->save();

                return response()->json([
                    'status'=>true,
                    'message'=>'registration successful',
                    'token' => $token,
                ], 200);



            }
        }else{


            return response()->json([
                'status'=>true,
                'message'=>'User Exist',
            ], 200);
        }



    }

    //validate user
    public function validateBank(Request $request){
        $user=$request->user()->id;
        $account=$request->account;
        $bank= $request->bank;


           $MyPaystack = new MyPaystack;

            $response = $MyPaystack->validateBank($bank, $account);


            foreach ($response as $use);

            $result = $use->account_name;

            if(!empty($result)){
                $submit =User::find($user);
                $submit->bank=$bank;
                $submit->account=$account;
                $submit->name=$result;
                $submit->save();

                $wallet = new Wallet();
                $wallet->user_id=$user;
                $wallet->save();

                return response()->json(['data'=>'Registeration successful'],201);
            }else{
                return response()->json(['data'=>'Wrong informations'],401);
            }



    }



}
