<?php

namespace App\Actions\Auth;

use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\OneTimePassword;

class LoginAction {
    public function run($request) {
        $phone = $request['phone'];
        //Generate random number for OTP
        $otp = Str::substr(rand(100000,999999), 0, 6);
        //body
        $body = 'Your Ganado verification code is: '.$otp.'. It will expire in 5 minutes.';

       //send otp
        $sendOTP = (new OneTimePassword($phone, $body));

        if($sendOTP) {
            // update the OTP field
            $user = User::where('phone', $phone)->update(['otp' => $otp]);


            if($user) {
                //Authenticate a user using passport
                $passwordGrantClient = Client::where('password_client', 1)->first();

                $data = [
                    'grant_type' => 'password',
                    'client_id' => $passwordGrantClient->id,
                    'client_secret' => $passwordGrantClient->secret,
                    'username' => $phone,
                    'password' => $phone,
                    'scope' => '*',
                ];

                $tokenRequest = Request::create('/oauth/token', 'post', $data);

                //return bearer token
                $tokenResponse = app()->handle($tokenRequest);

                return [
                    "response" => $tokenResponse,
                    "content" => json_decode($tokenResponse->content(), true)
                ];
            }
        }
    }
}
