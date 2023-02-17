<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response()->json(User::all());
    }

    public function onLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(array("success" => 0, "error_code" => "error_validate_error"), 401);
        }

        $user = User::where("email", $request->email)->get();
        if ($user->count() > 0) {
            if (Hash::check($request->password, $user[0]->password)) {
                return Response()->json(array("success" => 1, "data" => $user[0]));
            } else {
                return response()->json(array("success" => 0, "error_code" => "error_incorrect_identical"), 401);
            }
        } else {
            return response()->json(array("success" => 0, "error_code" => "error_incorrect_identical"), 401);
        }
        return response()->json(array("success" => 0, "error_code" => "error_unknow"), 400);
    }

    public function onRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        try {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }


            $postArray = [
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'remember_token' => $request->token,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                // 'avatar' => $request->file('UrlImage')->getClientOriginalName()
            ];

            // if ($request->hasFile('UrlImage')) {
            //     $image = $request->file('UrlImage');
            //     $name = $image->getClientOriginalName();
            //     $destinationPath = public_path('/upload/images');
            //     $imagePath = $destinationPath . "/" . $name;
            //     $image->move($destinationPath, $name);
            // }
            $user = User::create($postArray);
            return Response()->json(array("success" => 1, "data" => $postArray));
        } catch (Exception $e) {
            return Response()->json(array("success" => 0, "error" =>  $e->getMessage()));
        }
    }
    public function getBalanceOfId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => "Require user id!"], 401);
        }

        $user = User::select('balance')->where("id", $request->id)->get();
        if ($user->count() > 0) {
            return Response()->json(array("success" => 1, "data" => $user[0]));
        }
        return response()->json(['error' => "Error, cannot find user!"], 401);
    }
    public function makeTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => "Require user id!"], 401);
        }

        $user = User::select('balance')->where("id", $request->id)->get();
        if ($user->count() > 0) {
            return Response()->json(array("success" => 1, "data" => $user[0]));
        }
        return response()->json(['error' => "Error, cannot find user!"], 401);
    }
    public function getInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => "Require user id!"], 401);
        }

        $user = User::select('name', 'email', 'phone', 'address', 'role', 'avatar', 'created_at', 'updated_at')->where("id", $request->id)->get();
        if ($user->count() > 0) {
            return Response()->json(array("success" => 1, "data" => $user[0]));
        }
        return response()->json(['error' => "Error, cannot find user!"], 401);
    }
}
