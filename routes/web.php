<?php

use App\Http\Controllers\API\CitizenServiceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\AccountManageController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\PermissionController;
use App\Http\Controllers\Frontend\PostsController;
use App\Http\Controllers\Frontend\RequestHistoryController;
use App\Http\Controllers\Frontend\RoleController;
use App\Http\Controllers\Frontend\ServiceConfigurationController;
use App\Http\Controllers\Frontend\SettingController;
use App\Http\Controllers\Frontend\ServiceKioskController;
use App\Http\Controllers\Frontend\UserLogController;
use App\Http\Controllers\Frontend\UserManageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckPermissionByRouteName;
use App\Http\Middleware\LogUserActions;
use App\Http\Middleware\RedirectAfterLogin;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('auth/login');
});


Route::middleware(['auth', 'verified', LogUserActions::class])->group(function () {
    Route::get('/redirect-to-highest-permission', [UserController::class, 'redirectToHighestPermission'])
        ->name('get.highest.permission.url');
    Route::get('posts/filter', [PostsController::class, 'filter'])->name('posts.filter');
    Route::get('accounts-manager/filter', [AccountManageController::class, 'filter'])->name('accountsmanager.filter');

    Route::middleware([CheckPermissionByRouteName::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/{id}', [DashboardController::class, 'show'])->name('dashboard.show');
        Route::get('dashboard/detail/citizen-service/{id}', [DashboardController::class, 'edit'])->name('dashboard.handle.citizen-service');
        Route::post('/dashboard/update/citizen-service/{id}', [DashboardController::class, 'update'])->name('dashboard.update.citizen-service');
        Route::post('/dashboard/cancel-process/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy.process');
        Route::post('/dashboard/citizen-service/update-status', [DashboardController::class, 'updateStatus'])->name('dashboard.citizen-service.update-status');
        Route::post('/dashboard/citizen-service/cancel-multiple', [DashboardController::class, 'cancelMultiple']);
        Route::resource('service-configurations', ServiceConfigurationController::class);

        Route::resource('posts', PostsController::class);


        Route::resource('accounts-manager', AccountManageController::class);

        Route::resource('user-roles-manager', RoleController::class);

        Route::resource('request-history', RequestHistoryController::class);

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store');

        Route::resource('service-kiosk-manager', ServiceKioskController::class);
        Route::get('/service-kiosk-manager/get-number/{id}', [ServiceKioskController::class, 'getNumber'])->name('service-kiosk-manager.get-number');
    });
    //System Setting


    Route::resource('user-logs', UserLogController::class);


    Route::post('/user-logs/delete', [UserLogController::class, 'destroyMultiple'])->name('user-logs.delete-multiple');
    Route::get('/user-logs/by-role/{roleId}', [UserLogController::class, 'getLogsByRole'])->name('user-logs.fitler-by-role');

    Route::post('/dashboard/citizen-service/update-read-ticket/{id}', [DashboardController::class, 'updateReadTicket'])->name('dashboard.citizen-service.update-read-ticket');
    Route::get('/dashboard/citizen-service/get-reviewed-tickets', [DashboardController::class, 'getReviewedTickets'])->name('dashboard.citizen-service.get-reviewed-tickets');
});


require __DIR__ . '/auth.php';
