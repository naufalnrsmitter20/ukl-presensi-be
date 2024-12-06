<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $att = Attendance::all();
            return new ApiResource("success", "attendance payload", $att);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
            $validation = Validator::make($request->all(), [
                "date" => "required",
                "time" => "required",
                "status" => "required",
                "user_id" => "required"
            ]);

            if($validation->fails()){
                return new ApiResource(401, $validation->errors(), null);
            }

            $validate_id = User::find($request->user_id);
            if(!$validate_id){
                return new ApiResource(401, "User not found", null);
            }


            $att = Attendance::create([
                "user_id" => $request->user_id,
                "date" => $request->date,
                "time" => $request->time,
                "status" => $request->status,
            ]);
            
                $payload = [
                "attendance_id" => $att->id,
                "user_id" => $att->user_id,
                "date" => $att->date,
                "time" => $att->time,
                "status" => $att->status,
                ];
            if(!$att){
                return new ApiResource(403, "Presensi Gagal Dicatat!", null);
            }
            return new ApiResource("success", "Presensi Berhasil Dicatat!", $payload);

            } catch (\Exception $e) {
                return response()->json([
                    "status" => false,
                    "message" => $e->getMessage()
                ], 500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         try {
            $validate_id = Attendance::find($id);
            if(!$validate_id){
                return new ApiResource(401, "Attendances not found", null);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                "date" => "required",
                "time" => "required",
                "status" => "required",
                "user_id" => "required"
            ]);

            $check_att_id = Attendance::find($id);

            if(!$check_att_id){
                return new ApiResource(401, "Attendances not found", null);
            }

            if($validation->fails()){
                return new ApiResource(401, $validation->errors(), null);
            }

            $validate_id = User::find($request->user_id);
            if(!$validate_id){
                return new ApiResource(401, "User not found", null);
            }


            $att = Attendance::find($id)->update([
                "user_id" => $request->user_id,
                "date" => $request->date,
                "time" => $request->time,
                "status" => $request->status,
            ]);
            
                $payload = [
                "attendance_id" => $att->id,
                "user_id" => $att->user_id,
                "date" => $att->date,
                "time" => $att->time,
                "status" => $att->status,
                ];
            if(!$att){
                return new ApiResource(403, "Presensi Gagal Diupdate!", null);
            }
            return new ApiResource("success", "Presensi Berhasil Diupdate!", $payload);

            } catch (\Exception $e) {
                return response()->json([
                    "status" => false,
                    "message" => $e->getMessage()
                ], 500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try {
            $user = Attendance::destroy($id);
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