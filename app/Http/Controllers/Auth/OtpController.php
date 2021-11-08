<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Use App\Models\User;
use App\Helpers\MyPaystack;
use App\Helpers\OneTimePassword;
use App\Models\savedToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use App\Models\Wallet;

class OtpController extends Controller
{
    //
    public function otp(Request $request){

        $user=$request->user()->id;

        $otp_verify=User::where('id',$user)->value('phone');

        $email=User::where('id',$user)->value('email');

        //delete saved Token
        $deleteToken= DB::table('saved_tokens')->where('email', $email)->delete();

        $otp = $request->otp;


        if(Wallet::where('id',$user)->exists()){
            $verification=new OneTimePassword();
            $verification->verification($otp_verify,$otp,$user);

            return response()->json(['data'=>'Registration successful']);
        }else{
            $wallet = new Wallet();
            $wallet->user_id=$user;
            $wallet->save();

            $verification=new OneTimePassword();
            $verification->verification($otp_verify,$otp,$user);

            return response()->json(['data'=>'Registration successful']);
        }









    }

    


}
