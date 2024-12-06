<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;

class SummaryAttendance extends Controller
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
            $month = "04";
            $year = "2023";
            $filteredAttendances = $att->filter(function($attendance) use ($month, $year){
                $attGetDate = explode("-", $attendance->date);
                return $attGetDate[0] === $year && $attGetDate[1] === $month;
            });

            $getstatushadir = $filteredAttendances->filter(function($item){
                return $item->status === "hadir";
            })->count();

            $getstatusizin = $filteredAttendances->filter(function($item){
                return $item->status === "izin";
            })->count();

            $getstatussakit = $filteredAttendances->filter(function($item){
                return $item->status === "sakit";
            })->count();

            $getstatusalpha = $filteredAttendances->filter(function($item){
                return $item->status !== "izin" && $item->status !== "sakit"  && $item->status !== "hadir";
            })->count();

            return new ApiResource("success", "success", [
                "user_id" => intval($id),
                "month" => ($month.'-'.$year),
                "attendance_summary" => [
                    "hadir" => $getstatushadir,
                    "izin" => $getstatusizin,
                    "sakit" => $getstatussakit,
                    "alpha" => $getstatusalpha,
                ],
                "detail" => $filteredAttendances
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}