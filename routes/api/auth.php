<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->name('login');

Route::middleware(Authenticate::using(guard: 'api'))->group(static function (): void {
    Route::post('logout', LogoutController::class)->name('logout');
});
