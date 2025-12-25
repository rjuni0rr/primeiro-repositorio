<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BundlesController;
use App\Http\Controllers\TicketDispenserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientAdminController;

use App\Http\Middleware\TicketDispenserSession;
use App\Http\Middleware\QueueDisplaySession;

use App\Http\Controllers\QueuesDisplayController;
use App\Http\Controllers\TicketCallerController;


// ----------------------------------------------------------------
// guest routes (unauthenticated users - public access)

Route::middleware(['guest'])->group(function (){

    // authentication (login)
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');

    // conclude new client admin registration
    Route::get('/conclude-registration/{code}', [AuthController::class, 'concludeRegistration'])->name('conclude.registration');
    Route::get('/define-password', [AuthController::class, 'definePassword'])->name('define.password');

    // define password
    Route::post('/define-password', [AuthController::class, 'definePasswordSubmit'])->name('define.password.submit');
    Route::get('/define-password-success', [AuthController::class, 'definePasswordSuccess'])->name('define.password.success');

    // password reset
    Route::get('/password-reset/{code}', [AuthController::class, 'passwordReset'])->name('password.reset');

    // recover password
    Route::get('/recover-password', [AuthController::class, 'recoverPassword'])->name('recover.password');
    Route::post('/recover-password', [AuthController::class, 'recoverPasswordSubmit'])->name('recover.password.submit');

    // define new password
    Route::get('/define-new-password/{code}', [AuthController::class, 'recoverPasswordDefineNew'])->name('recover.password.define.new');
    Route::post('/define-new-password', [AuthController::class, 'recoverPasswordDefineNewSubmit'])->name('recover.password.define.new.submit');

});


// ----------------------------------------------------------------
// auth routes (just for client-admin and client-user)

Route::middleware(['auth', 'can:client'])->group(function(){

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

    // perm delete
    Route::get('/queue/perm-delete/{id}', [MainController::class, 'permDeleteQueue'])->name('perm.queue.delete');
    Route::get('/queue/perm-delete-confirm/{id}', [MainController::class, 'permDeleteQueueConfirm'])->name('perm.queue.delete.confirm');

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
    Route::get('/caller/queue-caller/{queue_id}/{ticket_id}/{status}', [TicketCallerController::class, 'queueCaller'])->name('caller.queue.caller');

    Route::get('/caller/massive-dismiss/{queue_id}', [TicketCallerController::class, 'massiveDismiss'])->name('caller.queue.massive.dismiss');
    Route::get('/caller/massive-dismiss/{queue_id}/confirm', [TicketCallerController::class, 'massiveDismissConfirm'])->name('caller.queue.massive.dismiss.confirm');

//    Route::get('/caller/queue-caller/not_attended/{queue_id}/{ticket_id}', [TicketCallerController::class, 'markTicketAsNotAttended'])->name('caller.queue.ticket.not.attended');
//    Route::get('/caller/queue-caller/dismissed/{queue_id}/{ticket_id}', [TicketCallerController::class, 'markTicketAsDismissed'])->name('caller.queue.ticket.dismissed');
});


#------------------------------------------------------------------
# SYS-ADMIN (just for admin)
Route::middleware(['auth', 'can:sys-admin'])->group(function(){

    // home
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');

    // create company
    Route::get('/admin/company/create', [AdminController::class, 'createCompany'])->name('admin.company.create');
    Route::post('/admin/company/create', [AdminController::class, 'createCompanySubmit'])->name('admin.company.create.submit');

    // company details
    Route::get('/admin/company/details/{id}', [AdminController::class, 'companyDetails'])->name('admin.company.details');

    // access control company
    Route::get('/admin/company/control-access/{id}', [AdminController::class, 'companyControlAccess'])->name('admin.company.control.access');
    Route::post('/admin/company/control-access', [AdminController::class, 'companyControlAccessSubmit'])->name('admin.company.control.access.submit');

    // delete company
    Route::get('/admin/company/delete/{id}', [AdminController::class, 'deleteCompany'])->name('admin.company.delete');
    Route::get('/admin/company/delete-confirm/{id}', [AdminController::class, 'deleteCompanyConfirm'])->name('admin.company.delete.confirm');
    Route::get('/admin/company/restore/{id}', [AdminController::class, 'restoreCompany'])->name('admin.company.restore');

    // perm delete company
    Route::get('/admin/perm-delete/{id}', [AdminController::class, 'permCompanyQueue'])->name('admin.perm.company.delete');
    Route::get('/admin/perm-delete-confirm/{id}', [AdminController::class, 'permDeleteCompanyConfirm'])->name('admin.perm.company.delete.confirm');

    // statistics
    Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');

});


// auth routes (just for client-admin)
Route::middleware(['auth', 'can:client-admin'])->group(function(){

    Route::get('/client-admin', [ClientAdminController::class, 'index'])->name('client.admin.home');

    // create user
    Route::get('/client-admin/user/create', [ClientAdminController::class, 'createUser'])->name('client.admin.create');
    Route::post('/client-admin/user/create', [ClientAdminController::class, 'createUserSubmit'])->name('client.admin.create.submit');

    // force password
    Route::get('/client-admin/user/force-password-reset/{id}', [ClientAdminController::class, 'forcePasswordReset'])->name('client.admin.user.password.reset');
    Route::get('/client-admin/user/force-password-reset/{id}/confirm', [ClientAdminController::class, 'forcePasswordResetConfirm'])->name('client.admin.user.password.reset.confirm');

    // deactivate and activate
    Route::get('/client-admin/user/deactivate/{id}', [ClientAdminController::class, 'deactivateUser'])->name('client.admin.user.deactivate');
    Route::get('/client-admin/user/activate/{id}', [ClientAdminController::class, 'activateUser'])->name('client.admin.user.activate');

    // block and unblock
    Route::get('/client-admin/user/block/{id}', [ClientAdminController::class, 'blockUser'])->name('client.admin.user.block');
    Route::post('/client-admin/user/block', [ClientAdminController::class, 'blockUserSubmit'])->name('client.admin.user.block.submit');
    Route::get('/client-admin/user/unblock/{id}', [ClientAdminController::class, 'unblockUser'])->name('client.admin.user.unblock');

    // delete and restore
    Route::get('/client-admin/user/delete/{id}', [ClientAdminController::class, 'deleteUser'])->name('client.admin.user.delete');
    Route::get('/client-admin/user/restore/{id}', [ClientAdminController::class, 'restoreUser'])->name('client.admin.user.restore');

    // perm delete
    Route::get('/client-admin/perm-delete/{id}', [ClientAdminController::class, 'permDeleteClient'])->name('client.admin.user.perm.delete');
    Route::get('/client-admin/perm-delete-confirm/{id}', [ClientAdminController::class, 'permDeleteClientConfirm'])->name('client.admin.user.perm.delete.confirm');

    // edit company
    Route::get('/client-admin/company/edit', [ClientAdminController::class, 'editCompany'])->name('client.admin.company.edit');
    Route::post('/client-admin/company/edit', [ClientAdminController::class, 'editCompanySubmit'])->name('client.admin.company.edit.submit');

});


// change password and logout routes (for all authenticated users)
Route::middleware('auth')->group(function () {

    // change password
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change-password', [AuthController::class, 'changePasswordSubmit'])->name('change.password.submit');

    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});


// ticket dispenser routes (public access)
Route::middleware([TicketDispenserSession::class])->group(function (){
    Route::get('/dispenser', [TicketDispenserController::class, 'index'])->name('dispenser');
    Route::post('/dispenser/get-bundle-data', [TicketDispenserController::class, 'getBundleData'])->name('dispenser.get.bundle.data');
    Route::post('/dispenser/get-ticket', [TicketDispenserController::class, 'getTicket'])->name('dispenser.get.ticket');
});

Route::get('/dispenser/credentials', [TicketDispenserController::class, 'credentials'])->name('dispenser.credentials');
Route::post('/dispenser/credentials', [TicketDispenserController::class, 'credentialsSubmit'])->name('dispenser.credentials.submit');

// queues display routes (public access)
Route::middleware([QueueDisplaySession::class])->group(function (){
    Route::get('/queues-display', [QueuesDisplayController::class, 'index'])->name('queues.display');
    Route::post('/queues-display/get-bundle-data', [QueuesDisplayController::class, 'getBundleData'])->name('queues.display.get.bundle.data');
});

Route::get('/queues-display/credentials', [QueuesDisplayController::class, 'credentials'])->name('queues.display.credentials');
Route::post('/queues-display/credentials', [QueuesDisplayController::class, 'credentialsSubmit'])->name('queues.display.credentials.submit');



