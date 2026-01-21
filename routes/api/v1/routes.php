<?php

use App\Http\Controllers\V1\HomeController;
use Illuminate\Support\Facades\Route;

/** Home */
Route::get('/', HomeController::class)->name('home');

/** Auth */
Route::prefix('auth')->name('auth.')->group(base_path(path: 'routes/api/v1/auth.php'));
