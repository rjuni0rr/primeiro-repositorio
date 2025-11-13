<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware(['guest'])->group(function (){

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login.submit');

});


Route::middleware(['auth'])->group(function (){

    Route::get('/', function (){
        echo "Home Page!";
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});
