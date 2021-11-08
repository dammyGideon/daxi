<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\MyPaystack;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Wallet;
use Carbon\Carbon;
use App\Models\Order;
class WalletController extends Controller
{
    //

    public function fundWallet(Request $request)
    {
        $user = $request->user()->id;
        $email = User::where('id', $user)->value('email');
        $amount = $request->amount;
        $paid=$amount.'00';

        $currency='NGN';

            $data = [
                'email' => $email,
                'amount' => $paid,
                'currency' => $currency,

            ];

                 $url = MyPaystack::getAuthorizationResponse($data);
                 if ($url) {
                    return $url;
                }


                return response()->json([
                    "success" => false,
                    "message" => "Unable to make payment, try again later",
                ]);



    }

    public function response(Request $request){
        $user = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'reference' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors(),
            ], 422);
        }

        $MyPaystack = new MyPaystack;

        $paymentDetails = $MyPaystack->getPaymentData($request->reference);
        $money = $paymentDetails['data']['amount'];
        $paid = number_format(($money / 100), 2);
        $casting = floatval(preg_replace('/[^\d.]/', '', $paid));

        DB::table('wallets')->where('user_id', $user)
        ->update([
            'balance' => $casting,
        ]);

        $response=[
            "status"=>true,
            "message"=>"Wallet Funded",

        ];
        return response($response,201);

    }

    public function payWithWallet(Request $request){
        $user=$request->user()->id;
        $amount=$request->amount;

        $rider =Order::where('user_id',$user)->value('rider_id');

        DB::update('update orders set amount = ? where id = ?',[$amount,$user]);
            
          
            $payment= new Payment();
            $payment->method="wallet";
            $payment->user_id=$user;
            $payment->rider_id=$rider;
            $payment->amount =$amount;
            $payment->date=Carbon::now();
            $payment->save();

            return response()->json([
                "data"=>"payment made Successfully"
            ]);


        }

    public function payWithCash(Request $request){
        $user=$request->user()->id;
        $amount=$request->amount;

        $rider =Order::where('user_id',$user)->value('rider_id');

        DB::update('update orders set amount = ? where id = ?',[$amount,$user]);
            
            $payment= new Payment();
            $payment->method="Cash";
            $payment->user_id=$user;
            $payment->rider_id=$rider;
            $payment->amount =$amount;
            $payment->date=Carbon::now();
            $payment->save();

            return response()->json([
                "data"=>"payment made Successfully with cash"
            ]);

    }


}
