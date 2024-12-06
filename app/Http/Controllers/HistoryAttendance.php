<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Models\User;

class HistoryAttendance extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        try {
            $validate_id = User::find($id);
            if(!$validate_id){
                return new ApiResource(401, "User not found", null);
            }
            $att = Attendance::where("user_id", $id)->get();
            return new ApiResource("success", "success", $att);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
       

    }
}