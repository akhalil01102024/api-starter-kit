<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/** Auth */
Route::prefix('auth')->name('auth.')->group(base_path(path: 'routes/api/auth.php'));

/** Home */
Route::get('/user', HomeController::class)->name('home');
