<?php

use App\Http\Controllers\Api\member\UserMembershipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\member\TrainingSessionController;

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

//member routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('sessions', [TrainingSessionController::class, 'listSessions']);
    Route::post('training-sessions/book', [TrainingSessionController::class, 'bookSession']);
    Route::post('training-sessions/cancel', [TrainingSessionController::class, 'cancelSession']);
});
Route::post('subscribe', [UserMembershipController::class, 'subscribeToMembership'])->middleware('auth:sanctum');

