<?php

use App\Http\Controllers\AnalysisAttendance;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoryAttendance;
use App\Http\Controllers\SummaryAttendance;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Api\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("/user", UserController::class, [
    "user.index", "user.destroy", "user.show", "user.store","user.update"
]);
Route::apiResource("/atendance", AttendanceController::class, [
    "atendance.index", "atendance.destroy", "atendance.show", "atendance.store","atendance.update"
]);

Route::post("/register", RegisterController::class)->name("register");
Route::post("/login", LoginController::class)->name(";ogin");
Route::post("/logout", LogoutController::class)->name("logout")->middleware(VerifyToken::class);
Route::get("/atendance/history/{id}", HistoryAttendance::class)->middleware(VerifyToken::class);
Route::get("/atendance/summary/{id}", SummaryAttendance::class)->middleware(VerifyToken::class);
Route::post("/atendance/analysist", AnalysisAttendance::class)->middleware(VerifyToken::class);