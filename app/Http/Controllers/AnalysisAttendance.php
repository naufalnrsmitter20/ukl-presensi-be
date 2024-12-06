<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AnalysisAttendance extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "start_date" => "required|date",
                "end_date" => "required|date",
                "group_by" => "required|string",
            ]);
            if($validation->fails()){
                return new ApiResource(401, $validation->errors(), null);
            }

            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            
            $data = Attendance::whereBetween("date", [$start, $end])->orWhereBetween("date", [$start, $end])->get();
            $total_user = $data->count();
            
            $total_hadir = $data->filter(function($item){
                return $item->status === "hadir";
            })->count();
            $total_sakit = $data->filter(function($item){
                return $item->status === "sakit";
            })->count();
            $total_izin = $data->filter(function($item){
                return $item->status === "izin";
            })->count();
            $total_alpa = $data->filter(function($item){
                return $item->status !== "izin" && $item->status !== "sakit"  && $item->status !== "hadir";
            })->count();

            $hadirpercentage = $total_hadir/$total_user * 100;
            $sakitpercentage = $total_sakit/$total_user * 100;
            $izinpercentage = $total_izin/$total_user * 100;
            $alpapercentage = $total_alpa/$total_user * 100;
            
            return new ApiResource("success", "success", [
                "analysis_period" => [
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                ],
                "grouped_analysis" => [
                    "group" => $request->group_by,
                    "total_users" => $total_user,
                    "attendance_rate" => [
                        "hadir_percentage" => floor($hadirpercentage)."%",
                        "izin_percentage" => floor($izinpercentage)."%",
                        "sakit_percentage" => floor($sakitpercentage)."%",
                        "alpha_percentage" => floor($alpapercentage)."%",
                    ],
                "total_attendance" => [
                    "hadir" => $total_hadir,
                    "izin" => $total_izin,
                    "sakit" => $total_sakit,
                    "alpa" => $total_alpa,
                ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}