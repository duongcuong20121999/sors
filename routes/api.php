<?php

use App\Http\Controllers\API\CitizenController;
use App\Http\Controllers\API\CitizenServiceController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\ZaloController;
use Illuminate\Support\Facades\Route;



Route::apiResource('services', ServiceController::class);
Route::apiResource('citizens', CitizenController::class);
Route::apiResource('posts', PostController::class);

Route::get('/get-phone', [ZaloController::class, 'getPhone']);
Route::get('/citizens/check-exist/{zalo_id}', action: [CitizenController::class, 'checkUserExist']);
Route::get('/citizen/info', action: [CitizenController::class, 'InfoCitizen']);

Route::post('/register-service', [CitizenServiceController::class, 'registerService']);
Route::get('/citizen-service/status-summary', [CitizenServiceController::class, 'summaryByZaloId']);
Route::get('/citizen-services/by-status', [CitizenServiceController::class, 'getByZaloAndStatus']);
Route::post('/citizen-services/cancel-status', [CitizenServiceController::class, 'cancelNew']);

Route::get('/time-update', [SettingController::class, 'getTimeUpdate']);


Route::post('/citizen-service/update-read-ticket/{id}', [CitizenServiceController::class, 'updateReadTicket']);
Route::get('/citizen-service/get-reviewed-tickets', [CitizenServiceController::class, 'getReviewedTickets']);

