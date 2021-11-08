<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


Use App\Models\User;
use App\Helpers\MyPaystack;
use App\Helpers\OneTimePassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;
use App\Models\savedToken;

class UserAuthController extends Controller
{
    //

    public function userRegister(Request $request){

        $password=bcrypt($request->phone);
        $email=$request->email;
        $phone=$request->phone;
        $role='user';
        $nullOTp = User::where('email',$email)->value('otp');

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
                ], 200);;
            }else{

                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users',
                    'phone' => 'required|unique:users',

                ]);
                if($validator->fails()){
                    return response([
                        'error' =>$validator->errors()->all()
                    ], 422);
                }

                $user = new User();

                $user->email       =$email;
                $user->password    =$password;
                $user->phone       =$phone;
                $user->role        =$role;
                $user->save();

                $otp= new OneTimePassword();
                $otp->sendOtp($phone);

                $token = $user->createToken('token')->accessToken;


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


}
