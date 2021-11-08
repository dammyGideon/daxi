<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MyPaystack;
use App\Models\Order;
use App\Models\User;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Support\Facades\DB;
use Validator;

class PaymentController extends Controller
{
    //

    //payment
    public function redirectToGateway(Request $request)
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


    //payment
    public function handleGatewayCallback(Request $request)
    {
        $user = $request->user()->id;
        $id = Order::where('user_id', $user)->value('user_id');
        $rider_id = Order::where('rider_id', $user)->value('rider_id');
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
        $admin_money= 0.1*$casting;
        $agentFee=$casting-$admin_money;

            DB::table('')
            ->where('user_id', $id)
            ->update([
                'userPayment' => $casting,

            ]);

        DB::table('payments')

                ->insert([
                    'user_id'=>$id,
                    'rider_id'=>$rider_id,
                    'amount' => $agentFee,
                ]);

            $response=[
                "status"=>true,
                "message"=>"payment successful",

            ];
            return response($response,201);

    }

}
