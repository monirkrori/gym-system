<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EquipmentController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\TrainerController;
use App\Http\Controllers\admin\UserMembershipController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------

*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth')->group(function () {
        //dashboard
        Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

        //..........................................Profile Route.........................................................
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        //..........................................Activity Route.........................................................
        Route::get('/activities', [ActiviteController::class, 'index'])->name('activities.index');
        //..........................................MemberShips Route.........................................................
        Route::resource('memberships',UserMembershipController::class);
        Route::get('/memberships.xlsx', [UserMembershipController::class, 'exportExcel'])->name('memberships.exports-excel');
        Route::get('exports/memberships-pdf', [UserMembershipController::class, 'exportPdf'])->name('memberships.export.pdf');
        //..........................................Trainers Route.........................................................
        Route::resource('trainers',TrainerController::class);
        Route::get('trainers/exports/{type}' , [TrainerController::class,'export'])->name('trainers.export');
        Route::resource('equipments', EquipmentController::class);


    });
});




