<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Helpers\OneTimePassword;
use Laravel\Passport\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterAction {

    public function run($request) {
        $registrationStatus;
        $user = new User;
        $role;
        if($request['type'] == 'user') {
            $userRole = Role::user()->first();
            $vehicleType = NULL;
            $registrationStatus = 'accepted';
            $role = 'user';
        } else if($request['type'] == 'rider') {
            $userRole = Role::rider()->first();
            $vehicleType = $request['vehicle_type'];
            $registrationStatus = 'pending';
            $role = 'rider';
        }
        $phone = $request['phone'];
        $email = $request['email'];

        //Generate random number for OTP
        $otp = Str::substr(rand(100000,999999), 0, 6);

        //body of sms
        $body = 'Your Ganado verification code is: '.$otp.'. It will expire in 5 minutes.';

        //send otp
        $sendOTP = (new OneTimePassword($phone, $body));
        
        if($sendOTP) {
            //create a new user
            $user->email = $email;
            $user->phone = $phone;
            $user->otp = $otp;
            $user->password = Hash::make($phone);
            $user->vehicle_type = $vehicleType;
            $user->role = $role;
            $user->registration_status = $registrationStatus;
            if($user->save()) {
                //assign role to user
                $user->roles()->attach($userRole->id);

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
