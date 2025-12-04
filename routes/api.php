<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

 
Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('login', [AuthController::class, 'login'])  ->middleware('throttle:5,1')->name('login');

 
Route::group(['middleware' => ['jwt.auth', 'throttle:60,1']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('throttle:10,1');
    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [UserController::class, 'index'])->middleware('role:Admin');  
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);
});

Route::post('/users/{user}/make-admin', [UserController::class, 'makeAdmin'])
    ->middleware(['auth:api', 'role:Admin', 'throttle:3,1']); 
