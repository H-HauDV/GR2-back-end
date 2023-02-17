<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coupon;
use Exception;

class CouponController extends Controller
{
    public function getCouponsOfUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user_id = $request->id;
        $user = User::find($user_id);
        $coupons = $user->coupons; // Returns a Laravel Collection
        return response(['coupons' => $coupons], 200); // Returns JSON object containing an array of candidates
    }
    public function createCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'transaction_id'=> 'required|string',
            'percent' => 'required|numeric',
            'max_credit' => 'required|numeric',
            'amount_of_coupon' => 'required|numeric',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $postArray = [
            'user_id'      => (int)$request->user_id,
            'transaction_id'      => $request->transaction_id,

            'percent'     => (float)$request->percent,
            'max_credit'  => (float)$request->max_credit,
            'amount_of_coupon'  => (int)$request->amount_of_coupon,


            'description'  => $request->description,
            'color'  => $request->color,
            'sub_color'  => $request->sub_color,
            'logo'  => $request->logo,
        ];
        //    return Response()->json(array("test" => 1, "request" =>  $postArray));

        try {
            $user = User::where('id', (int)$request->user_id)->first();
            if ($user == null) {
                return response()->json(['error' => "User id error"], 401);
            }
            if ($user->role != "company") {
                return response()->json(['error' => "Not permitted"], 401);
            }
            // $old_balance = $user->balance;
            // //total money to make coupon
            // $payment = (float)$request->max_credit * (int)$request->amount_of_coupon;
            // //if total money to make coupon < balance return error
            // if ($old_balance < $payment) {
            //     return response()->json(['error' => "Not enough balance"], 401);
            // }
            // //balance after make coupon
            // $new_balance = $old_balance - $payment;
            // //update balance
            // $user->update(['balance' => $new_balance]);
            $coupon = Coupon::create($postArray);

            return response(['Created coupon' => $coupon], 200); // Returns JSON object containing an array of candidates
        } catch (Exception $e) {
            return response(['Error' => $e->getMessage()], 500);
        }
    }
}
