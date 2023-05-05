<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\EmailVerificationPromptController;
use App\Http\Controllers\Admin\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\VerifyEmailController;





Route::prefix('teacher')->middleware('theme:admin')->name('teacher.')->group(function(){

    Route::middleware(['guest:teacher'])->group(function(){
        Route::view('/login','auth.login')->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);
        Route::view('/register','auth.register')->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);
    });


    Route::middleware(['auth:teacher'])->group(function(){
        Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    });
});


