<?php

use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\EquipmentController;
use App\Http\Controllers\dashboard\MealPlanController;
use App\Http\Controllers\dashboard\MembershipPackageController;
use App\Http\Controllers\dashboard\MembershipPlanController;
use App\Http\Controllers\dashboard\ProfileController;
use App\Http\Controllers\dashboard\TrainerController;
use App\Http\Controllers\dashboard\TrainingSessionController;
use App\Http\Controllers\dashboard\UserMembershipController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

use App\Models\MembershipPackage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------

*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth')->group(function () {
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
        Route::get('dashboard/reports', [DashboardController::class, 'reports']);

        //..........................................Profile Route.........................................................
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        //..........................................Activity Route.........................................................
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        //..........................................Members Route.........................................................
        Route::resource('memberships', UserMembershipController::class);
        Route::get('/memberships.xlsx', [UserMembershipController::class, 'exportExcel'])->name('memberships.exports-excel');
        Route::get('exports/memberships-pdf', [UserMembershipController::class, 'exportPdf'])->name('memberships.export.pdf');
        //..........................................Trainers Route.........................................................
        Route::resource('trainers', TrainerController::class);
        Route::get('trainers/exports/{type}', [TrainerController::class, 'export'])->name('trainers.export');
        //..........................................Equipment Route.........................................................
        Route::resource('equipments', EquipmentController::class);
        //..........................................tranaing sessions Route.........................................................
        Route::resource('sessions', TrainingSessionController::class);
        //..........................................membership-plans Route.........................................................
        Route::resource('membership-plans', MembershipPlanController::class);
        //..........................................membership-packages Route.........................................................
        Route::resource('membership-packages', MembershipPackageController::class);
        //..........................................meal-plans Route.........................................................
        Route::resource('meal-plans', MealPlanController::class);


        Route::resources([
            'roles' => RoleController::class,
            'users' => UserController::class,
        ]);


    });
});




