<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;

Route::middleware(['guest'])->group(function (){

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

});


Route::middleware(['auth'])->group(function (){

    Route::get('/', [MainController::class, 'index'])->name('home');

    //    create a new queue
    Route::get('/queue/create', [MainController::class, 'createQueue'])->name('queue.create');
    Route::post('/queue/create', [MainController::class, 'createQueueSubmit'])->name('queue.create.submit');
    Route::get('queue/generate-hash', [MainController::class, 'generateQueueHash'])->name('queue.generate.hash');

    // edit queue
    Route::get('/queue/edit/{id}', [MainController::class, 'editQueue'])->name('queue.edit');
    Route::post('/queue/edit/', [MainController::class, 'editQueueSubmit'])->name('queue.edit.submit');


    //    queue details
    Route::get('/queue/{id}', [MainController::class, 'queueDetails'])->name('queue.details');

    //    change password
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change-password', [AuthController::class, 'changePasswordSubmit'])->name('change.password.submit');

    //    logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});
