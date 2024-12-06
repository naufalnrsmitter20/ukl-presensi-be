<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller 
{
    public function index(){
        try {
            $user = User::all();
            return new ApiResource("success", "user payload", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    public function show($id){
        try {
            $check_user_id = User::find($id);
            if(!$check_user_id){
                return new ApiResource(401, "User not found", null);
            }
            $user = User::find($id)->all();
            return new ApiResource("success", "Data User", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request){
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
    public function update(Request $request, $id){
        try {
            $validation = Validator::make($request->all(), [
                "name" => "required",
                "username" => "required|unique:users,username",
                "password" => "required",
                "role" => "required"
            ]);
            $validate_id = User::find($id);
            if(!$validate_id){
                return new ApiResource(401, "User not found", null);
            }

            if($validation->fails()){
                return new ApiResource(401, $validation->errors(), null);
            }
            $userPayload = [
                "name" => $request->name,
                "username" => $request->username,
                "password" => bcrypt($request->password),
                "role" => $request->role,
            ];  
            $user = User::find($id)->update($userPayload);
            return new ApiResource("success", "Update Success", $userPayload);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id){
        try {
            $user = User::destroy($id);
            if(!$user){
                return new ApiResource(401, "Failed to Delete", null);

            }
            return new ApiResource("success", "Delete Success", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}