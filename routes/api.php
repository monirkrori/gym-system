<?php


use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Member\GetTrainerController;
use App\Http\Controllers\Api\Member\GetTrainingSessionController;
use App\Http\Controllers\Api\Member\SubscribeController;
use App\Http\Controllers\Api\Trainer\TrainerAttendanceController;
use App\Http\Controllers\Api\Trainer\TrainingSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Member\RatingController;
use App\Http\Controllers\Api\Member\BookingController;
use App\Http\Controllers\Api\Member\MealPlanController;
use App\Http\Controllers\Api\Member\AttendanceLogController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//auth routes
Route::prefix('auth')->group(function () {

    // AuthController routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->middleware('auth:sanctum');

    // ForgotPasswordController routes
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('reset-password', [ForgotPasswordController::class, 'reset']);
});
//member routes

//---TrainingSession Routes----
Route::middleware('auth:sanctum')->group(function () {
    Route::get('sessions', [GetTrainingSessionController::class, 'listSessions']);
    Route::get('sessions/{id}', [GetTrainingSessionController::class, 'show']);
});

//---subscribe Routes-----
Route::post('subscribe', [SubscribeController::class, 'subscribeToMembership'])->middleware('auth:sanctum');
Route::post('subscribe-package', [SubscribeController::class, 'subscribeToPackage'])->middleware('auth:sanctum');

//get trainers
Route::get('trainers-list',[GetTrainerController::class,'listTrainer']);

//---Booking Routes------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('sessions-booking/book', [BookingController::class, 'bookSession']);
    Route::post('sessions-booking/cancel', [BookingController::class, 'cancelSession']);
    Route::get('sessions-booking/History', [BookingController::class, 'getBookingHistory']);
    Route::get('sessions-booking/UsageReport', [BookingController::class, 'getUsageReport']);
});

//---Rating Routes-------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::put('/ratings/{id}', [RatingController::class, 'update']);
    Route::get('/ratings/{rateable_id}', [RatingController::class, 'show']);
    Route::post('/ratings/{ratingId}/reply', [RatingController::class, 'reply']);
});

//---attendance Routes---------
Route::post('/attendance', [AttendanceLogController::class, 'store'])->middleware('auth:sanctum'); // Register attendance
//Route::get('/attendance/user/{userId}', [AttendanceController::class, 'getUserAttendance']); // Get user's attendance log

//---MealPlan Routes-----------
Route::post('/subscribe-meal-plan', [MealPlanController::class, 'subscribe'])->middleware('auth:sanctum'); // Subscribe to a meal plan
Route::get('/show-meal-plan/{id}', [MealPlanController::class, 'show'])->middleware('auth:sanctum'); // Subscribe to a meal plan
//Route::get('/user/{userId}/meal-plans', [MealPlanController::class, 'getUserMealPlans']); // Get user's subscribed meal plans


Route::prefix('trainer')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('sessions', TrainingSessionController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/trainer/sessions', [TrainerAttendanceController::class, 'getSessions']);
    Route::get('/trainer/sessions/{sessionId}/attendance', [TrainerAttendanceController::class, 'getAttendance']);
    Route::get('/trainer/sessions/{sessionId}/report', [TrainerAttendanceController::class, 'getAttendanceReport']);
});
