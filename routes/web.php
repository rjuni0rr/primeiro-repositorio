<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralController;



// ----------------------------------------------------------------
// guest routes (unauthenticated users - public access)

Route::middleware(['guest'])->group(function (){

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-submit', [AuthController::class, 'login-submit'])->name('login.submit');


    // terms and conditions
    Route::get('/terms-and-conditions', [GeneralController::class, 'termsAndConditions'])->name('terms.conditions');

    // changelog
    Route::get('/changelog', [GeneralController::class, 'changelog'])->name('changelog');

});


// ----------------------------------------------------------------
// auth routes (just for client-admin and client-user)

Route::middleware(['auth'])->group(function () {

    // change password
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change-password', [AuthController::class, 'changePasswordSubmit'])->name('change.password.submit');

    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});
