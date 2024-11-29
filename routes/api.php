<?php

use App\Http\Controllers\Api\AttendanceLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrainingSessionController;
use App\Http\Controllers\Api\UserMembershipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/training-sessions/{trainingSession}/status', [TrainingSessionController::class, 'show']);
    Route::put('/user-memberships/{userMembership}', [UserMembershipController::class, 'updateStatus']);
    Route::post('/attendance-checkin', [AttendanceLogController::class, 'checkIn']);
    Route::put('/attendance-checkout/{attendanceLog}', [AttendanceLogController::class, 'checkOut']);
});

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
