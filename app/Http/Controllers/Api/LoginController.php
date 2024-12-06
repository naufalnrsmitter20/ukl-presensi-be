<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
        ]);

        if($validation->fails()){
            return new ApiResource(401, $validation->errors(), null);
        }

        $credentials = $request->only(["username","password"]);
        if(!$token = auth()->guard("api")->attempt($credentials)){
            return new ApiResource(401, "Username atau password salah!", null);
        }
        return response()->json([
            "status" => "success",
            "message" => "Login Berhasil!",
            "token" => $token
        ], 200);
    }
}