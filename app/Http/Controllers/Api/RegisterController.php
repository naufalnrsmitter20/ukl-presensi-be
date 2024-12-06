<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "name" => "required",
                "username" => "required|unique:users,username",
                "password" => "required",
                "role" => "required"
            ]);

            if($validation->fails()){
                return new ApiResource(401, $validation->errors(), null);
            }
            $userPayload = [
                "name" => $request->name,
                "username" => $request->username,
                "password" => bcrypt($request->password),
                "role" => $request->role,
            ];                                              

            $user = User::create($userPayload);
            if(!$user){
                return new ApiResource("403", "Failed to create user", null);
            }
            return new ApiResource("success", "Success to create user", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}