<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coupon;
use Exception;

class TransactionController extends Controller
{
    public function createTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'apikey' => 'required|string',
            'contractaddress' => 'required',
            'from' => 'required',
            'to' => 'required',
            'password' => 'required',
            'amount' => 'required',
        ]);
        # ----- REPLACE THE VARIABLES BELOW WITH YOUR DATA -----
        $apikey = $request->apikey; // API Key in your account panel
        $contractaddress = $request->contractaddress; // Smart contract address of the Token
        $from = $request->from; // Binancecoin address you want to send from (must have been created with Chaingateway.io)
        $to = $request->to; // Receiving Binancecoin address
        $password = $request->password; // Password of the Binancecoin address (which you specified when you created the address)
        $amount = $request->amount; // Amount of Tokens to send
        # -------------------------------------------------------

        # Define function endpoint
        $ch = curl_init("https://eu.bsc.chaingateway.io/v1/sendToken");

        # Setup request to send json via POST. This is where all parameters should be entered.
        $payload = json_encode(array("contractaddress" => $contractaddress, "from" => $from, "to" => $to, "password" => $password, "amount" => $amount));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "Authorization: " . $apikey));

        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);

        # Decode the received JSON string
        $resultdecoded = json_decode($result, true);

        return response()->json(['result' => $resultdecoded], 200);

        # Print the transaction id of the transaction
        // echo $resultdecoded["txid"];
    }
}
