<?php


use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/log-out', [AuthController::class, 'logout']);
    Route::get('/auth-user', [AuthController::class, 'user']);

    Route::get('ticket-data', [TicketController::class, 'initData']);
    Route::apiResource('ticket', TicketController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::apiResource('ticket/{ticket}/comment', TicketCommentController::class)->only(['store', 'destroy']);
    Route::apiResource('users', UserController::class)->only(['store','index']);
    Route::get('roles', [RoleController::class, 'index']);


});
