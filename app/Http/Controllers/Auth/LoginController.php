<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\UserInterface;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Validator;
use App\Helpers\OneTimePassword;
use App\Models\User;

class LoginController extends Controller
{
    //

    public function login(Request $request)
      {

           $validator = Validator::make($request->all(),[

                'phone'=> 'required',
            ]);

            if($validator->fails()){
                return response([
                    'error' =>$validator->errors()->all()
                ], 422);
            }
            $phone=$request->phone;
            $data = [
                'role'=>'user',
                'phone' => $phone,
                'password' => $phone
            ];
            $result=User::where('phone',$phone)->value('otp');
            if(auth()->attempt($data)){

                if(empty($result)){
                    $otp= new OneTimePassword();
                    $otp->sendOtp($phone);
                }else{
                    $token = auth()->user()->createToken('token')->accessToken;
                    return response()->json([
                        "status"=>'success',
                        'token' => $token],
                        200);
                }
            }

         return response()->json(['error' => 'Unauthorized'], 401);



      }



      public function login_rider(Request $request)
      {
           $email=$request->email;
           $validator = Validator::make($request->all(),[
                'phone'=> 'required',
            ]);

            if($validator->fails()){
                return response([
                    'error' =>$validator->errors()->all()
                ], 422);
            }
            $phone=$request->phone;
          $data = [
              'role'=>'rider',
              'phone' => $phone,
              'password' => $phone
          ];

          $result=User::where('phone',$phone)->value('otp');
          if(auth()->attempt($data)){

            if($result ==0){
                $otp= new OneTimePassword();
                $otp->sendOtp($phone);
            }else{

                $token = auth()->user()->createToken('token')->accessToken;
                return response()->json([
                    "status"=>'success',
                    'token' => $token],
                    200);
            }
          }

        return response()->json(['error' => 'Unauthorized'], 401);
    }



        //authentication
        public function authenticatedUser(Request $request){
            $user=$request->user();
            $response=[
                'success'=>true,
                'data'=>$user,
            ];
            return response($response,201);
       }


          //facebook login
    //facebook redirect
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function facebookCallback(Request $request)
    {   
        $user = Socialite::driver('facebook')->stateless()->user();
        $existingUser = User::where('email', $user->email)->first();       
        
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
            return response()->json($existingUser,200);
        } else {
           // create a new user
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider' => 'facebook',
                'provider_id' => $user->id
            ]);

           // login the new user
            auth()->login($newUser, true);
            return response()->json(['data'=>$newUser, 'token' => $accessToken],200);
        }
      
        return response()->json(['message'=> 'Unable to get your Account Information'], 401);
    }



    //google login
    //google redirect
    /**
  * Redirect the user to the Google authentication page.
  *
  * @return \Illuminate\Http\Response
  */
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $existingUser = User::where('email', $user->email)->first();       
        
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
            return response()->json(['data'=> $existingUser],200);
        } else {
           // create a new user
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider' => 'google',
                'provider_id' => $user->id
            ]);     

           // login the new user
            auth()->login($newUser, true);
            return response()->json(['data' => $newUser, 'token' =>$user->token], 200);
        }
      
        return response()->json(['message'=> 'Unable to get your Account Information'], 401);
    }



}
