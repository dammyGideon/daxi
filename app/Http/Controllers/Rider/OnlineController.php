<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bank;
use App\Helpers\MyPaystack;
use App\Models\onlineRiders;
use App\Models\Order;
use Carbon\Carbon;
class OnlineController extends Controller
{
    //

    public function getOnline(Request $request){
        $user=$request->user()->id;
        $active=DB::table('users')->where('id',$user)->value('active');
        if($active == 0){
            DB::table('users')->where('id',$user)->update(['active'=>1]);
            DB::table('online_riders')->where('rider_id',$user)->update(['active'=>1]);
            return response()->json([
                'success' => true,
                'rider_id'=>$user,
                'message' => 'You are online'
            ]);
        }else{
            DB::table('users')->where('id',$user)->update(['active'=>0]);
            DB::table('online_riders')->where('rider_id',$user)->update(['active'=>0]);
            return response()->json([
                'success' => true,
                'message' => 'You are offline'
            ]);
        }

    }

    //list of banks
    public function bankList(){
        $banks =new MyPaystack();
        $response =$banks->getBank();
        return $response;

    }

    public function makeOrder(Request $request){
        $user=$request->user()->id;
        $rider=$request->Driver;
        $presentLat=$request->present_lat;
        $presentLog=$request->present_log;
        $destinationLat=$request->destination_lat;
        $destinationLog=$request->destination_log;
        $pickup=$request->pickup;
        $dropOf=$request->dropOf;


            $checkUser=Order::where('user_id',$user)->exists();
         
            if($checkUser){
                return response()->json([
                    'success' => true,
                    'message' => 'You have pending order'
                ]);
            }else{
            $order= new Order();
            $order->user_id=$user;
            $order->rider_id=$rider;
            $order->present_lat=$presentLat;
            $order->present_log=$presentLog;
            $order->destination_lat=$destinationLat;
            $order->destination_log=$destinationLog;
            $order->pickup=$pickup;
            $order->dropOf=$dropOf;
            $order->save();

            DB::table('notifications')->insert([
                'user_id'=>$rider,
                'message'=>"you ride was ordered",
                "date"=>carbon::now()
                ]);
            return response()->json([
                'success' => true,
                'message' => 'You are make order'
            ]);
            }





    }

    public function viewOrder(Request $request){
        $user =$request->user()->id;
        $order =DB::table('orders')->where('rider_id',$user)->get();
        return $order;
    }

}
