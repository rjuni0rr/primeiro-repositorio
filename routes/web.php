<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BundlesController;
use App\Http\Controllers\TicketDispenserController;
use App\Http\Middleware\TicketDispenserSession;
use App\Http\Middleware\QueueDisplaySession;
use App\Http\Controllers\QueuesDisplayController;
use App\Http\Controllers\TicketCallerController;

// ----------------------------------------------------------------
// guest routes
Route::middleware(['guest'])->group(function (){

    // authentication (login)
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

});

// ----------------------------------------------------------------
// auth routes
Route::middleware(['auth'])->group(function(){

    Route::get('/', [MainController::class, 'index'])->name('home');

    # QUEUES --------------------------------------------------------------

    // create a new queue
    Route::get('/queue/create', [MainController::class, 'createQueue'])->name('queue.create');
    Route::post('/queue/create', [MainController::class, 'createQueueSubmit'])->name('queue.create.submit');
    Route::get('/queue/generate-hash', [MainController::class, 'generateQueueHash'])->name('queue.generate.hash');

    // edit queue
    Route::get('/queue/edit/{id}', [MainController::class, 'editQueue'])->name('queue.edit');
    Route::post('/queue/edit', [MainController::class, 'editQueueSubmit'])->name('queue.edit.submit');

    // clone a queue
    Route::get('/queue/clone/{id}', [MainController::class, 'cloneQueue'])->name('queue.clone');
    Route::post('/queue/clone', [MainController::class, 'cloneQueueSubmit'])->name('queue.clone.submit');

    // delete a queue
    Route::get('/queue/delete/{id}', [MainController::class, 'deleteQueue'])->name('queue.delete');
    Route::get('/queue/delete-confirm/{id}', [MainController::class, 'deleteQueueConfirm'])->name('queue.delete.confirm');

    // restore deleted queue
    Route::get('/queue/restore/{id}', [MainController::class, 'restoreQueue'])->name('queue.restore');

    // queue details
    Route::get('/queue/{id}', [MainController::class, 'queueDetails'])->name('queue.details');

    # BUNDLES --------------------------------------------------------------

    Route::get('/bundles', [BundlesController::class, 'index'])->name('bundles.home');
    Route::get('/bundles/create', [BundlesController::class, 'createBundle'])->name('bundles.create');
    Route::post('/bundles/create', [BundlesController::class, 'createBundleSubmit'])->name('bundles.create.submit');
    Route::get('/bundles/generate-credential-value/{num_chars}', [BundlesController::class, 'generateCredentialValue'])->name('bundles.generate.credential.value');

    Route::get('/bundles/edit/{id}', [BundlesController::class, 'edit'])->name('bundles.edit');
    Route::post('/bundles/edit', [BundlesController::class, 'editSubmit'])->name('bundles.edit.submit');

    Route::get('/bundles/delete/{id}', [BundlesController::class, 'delete'])->name('bundles.delete');
    Route::get('/bundles/delete-confirm/{id}', [BundlesController::class, 'deleteConfirm'])->name('bundles.delete.confirm');
    Route::get('/bundles/restore/{id}', [BundlesController::class, 'restore'])->name('bundles.restore');

    # CALLER ---------------------------------------------------------------
    Route::get('/caller', [TicketCallerController::class, 'index'])->name('caller.home');
    Route::get('/caller/queue-details/{id}', [TicketCallerController::class, 'queueDetails'])->name('caller.queue.details');


    # USER -----------------------------------------------------------------

    // change password
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change-password', [AuthController::class, 'changePasswordSubmit'])->name('change.password.submit');

    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


// ticket dispenser routes
Route::middleware([TicketDispenserSession::class])->group(function (){
    Route::get('/dispenser', [TicketDispenserController::class, 'index'])->name('dispenser');
    Route::post('/dispenser/get-bundle-data', [TicketDispenserController::class, 'getBundleData'])->name('dispenser.get.bundle.data');
    Route::post('/dispenser/get-ticket', [TicketDispenserController::class, 'getTicket'])->name('dispenser.get.ticket');
});

Route::get('/dispenser/credentials', [TicketDispenserController::class, 'credentials'])->name('dispenser.credentials');
Route::post('/dispenser/credentials', [TicketDispenserController::class, 'credentialsSubmit'])->name('dispenser.credentials.submit');

// queues display routes
Route::middleware([QueueDisplaySession::class])->group(function (){
    Route::get('/queues-display', [QueuesDisplayController::class, 'index'])->name('queues.display');
    Route::post('/queues-display/get-bundle-data', [QueuesDisplayController::class, 'getBundleData'])->name('queues.display.get.bundle.data');
});

Route::get('/queues-display/credentials', [QueuesDisplayController::class, 'credentials'])->name('queues.display.credentials');
Route::post('/queues-display/credentials', [QueuesDisplayController::class, 'credentialsSubmit'])->name('queues.display.credentials.submit');



