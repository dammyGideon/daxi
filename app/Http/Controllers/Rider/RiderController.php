<?php

namespace App\Http\Controllers\Rider;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\sendPosition;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\notification;
use Carbon\Carbon;

class RiderController extends Controller
{

    //push notification
    public function getlocation(Request $request) {
        $user=$request->user()->id;
        $lat=$request->latitude;
        $log=$request->longitude;




        $location = [
            "lat" => $lat,
            "log" => $log,

        ];

        event(new sendPosition($location));



            DB::table('users')->where('id',$user)->update([
                "latitude"=>$lat,
                "longitude"=>$log
            ]);
            return response()->json(['data'=>"location updated"]);

    }

    //get rider location



    // $riderLocation =User::where('id',$user)->value('active');





    public function addVehicle(Request $request) {
        $user=$request->user()->id;
        $insert = DB::table('vehicles')->insert([
            'rider_id'=>$user,
            'brand'=>$request->brand,
            'model'=>$request->model,
            'plate_number'=>$request->plate_number,
            'color'=>$request->color,
            'seatNo'=>$request->seatNo
            ]);

        if($insert) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle saved successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Unable to add vehicle'
        ]);
    }

    //show vehicles
    public function vehicle(Request $request){
        $user=$request->user()->id;
        $vehicle=DB::select('select * from vehicles where rider_id = ?', [$user]);
        return response()->json(['data'=>$vehicle]);
    }

    public function updateVechicle(Request $request){
        $user=$request->user()->id;
        $data=[
            'brand'=>$request->brand,
            'model'=>$request->model,
            'plate_number'=>$request->plate_number,
            'color'=>$request->color
        ];
        $response=Vehicle::where('rider_id',$user)->update(
            [
                "brand"=>$data['brand'],
                "model"=>$data['model'],
                "plate_number"=>$data['plate_number'],
                "color"=>$data['color']
            ]
        );

        return response()->json([
            "status"=>true,
            "data"=>"Vehicle Update was successful"
        ],201);

    }


    public function acceptOrder(Request $request){
        $driver=$request->user()->id;

        $user=$request->riderId;

        DB::table('orders')->where('user_id',$user)->update([
                'acceptOrder'=>1
        ]);

        $message="Your Rider has Being Accepted he will be with Shortly";
        $info =Order::where('rider_id',$driver)->value('user_id');
        $notification= new notification();
        $notification->user_id=$info;
        $notification->message=$message;
        $notification->date=Carbon::now();
        $notification->save();


        return response()->json(['data'=>"rider has being accepted"],200);
    }

    public function declineOrder(Request $request){
        $driver=$request->user()->id;

        $rider=$request->riderId;
        DB::table('orders')->where('user_id',$rider)->delete();

        $message="Ride Was cancelled by the Driver";
        $info =Order::where('rider_id',$driver)->value('user_id');
        $notification= new notification();
        $notification->user_id=$info;
        $notification->message=$message;
        $notification->date=Carbon::now();
        $notification->save();

        return response()->json(['data'=>"ride Cancelled"],200);
    }







}
