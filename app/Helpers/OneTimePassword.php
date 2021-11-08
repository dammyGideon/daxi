<?php


namespace App\Helpers;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\DB;
class OneTimePassword
{



    public function sendOtp($phone)
    {
		$token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create('+234'.$phone, "sms");

        return $twilio ;
    }

    //otp verification
    public function verification($otp_verify,$otp,$user){
        
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
        ->verificationChecks
            ->create($otp, array('to' =>'+234'. $otp_verify));

            if ($verification->valid) {
                $user =DB::table('users')->where('id',$user )->update(['otp' => true]);
                     $response = [
                    'status' => true,
                    'message' => 'Verification successful'
                ];
                  return response($response, 201);
                 } else{
                $response = [
                    'status' => false,
                    'message' => 'Verification is wrong'
                ];
                return response($response, 401);
            }
    }


}
