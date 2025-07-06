<?php

use App\Http\Controllers\LuckyLinkController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegisterController::class, 'show'])
    ->name('register.form');;
Route::post('/register', [RegisterController::class, 'store'])
    ->name('register');

Route::prefix('link/{token}')
    ->controller(LuckyLinkController::class)
    ->middleware('link-active')
    ->group(function () {
        Route::get('/', 'show')->name('link.show');
        Route::post('/deactivate', 'deactivate')->name('link.deactivate');
        Route::post('/regenerate', 'regenerate')->name('link.regenerate');
        Route::post('/lucky', 'lucky')->name('link.lucky');
        Route::get('/history', 'history')->name('link.history');
    });
