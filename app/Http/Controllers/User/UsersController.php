<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\notification;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Wallet;
Use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\cancelNotification;
use Validator;
class UsersController extends Controller
{




    public function getUserById(Request $request){
        $user = $request->user()->id;
        $login=User::find($user);


        if(!empty($user)){
            return response()->json([
            'status' => true,
           'data' => $login,
         ]);
        }
        return response()->json([
             'status' => false,
            'message' => 'User does not exist',
        ]);

    }

   public function getWallet(Request $request){
        $user=$request->user()->id;
        $wallet =DB::select('select * from wallets where user_id = ?', [$user]);
        return response()->json(["data"=>$wallet]);
   }

   public function searchRider(Request $request){

       $user=$request->user()->id;
       $radius=400;

       $log=User::where('id',$user)->value('longitude');
       $lat=User::where('id',$user)->value('latitude');


       $data = DB::table('users')
       ->selectRaw("id,phone,longitude,latitude,vehicle_type,picture,name,
        ( 6371000 * acos( cos( radians(?) ) *
          cos( radians( latitude ) )
          * cos( radians( longitude ) - radians(?)
          ) + sin( radians(?) ) *
          sin( radians( latitude ) ) )
        ) AS distance", [$lat, $log, $lat])
         ->where('active', '=', 1)
        ->having("distance", "<", $radius)
        ->orderBy("distance",'asc')->get();




        return response()->json(['data'=>$data]);
   }


   //cancel rider
   public function cancelRider(Request $request){
    $user=$request->user()->id;
    $rider=DB::table('orders')->where('user_id',$user)->value('rider_id');

    $message="Client Cancelled the rider ";

    $response = new cancelNotification();
    $response->user_id=$rider;
    $response->message=$message;
    $response->date=Carbon::now();
    $response->save();

    DB::table('orders')->where('user_id',$user)->delete();

    return response()->json(['data'=>$message]);

   }

   public function notification(Request $request){
    $user=$request->user()->id;
    $notification = DB::select('select * from notifications where user_id = ?', [$user]);
    return response()->json(['data'=>$notification]);
   }

   public function histroy(Request $request){
    $user=$request->user()->id;
    $history= DB::select('select * from histories where user_id = ?', [$user]);
    return response()->json(['data'=>$history]);
   }



}
