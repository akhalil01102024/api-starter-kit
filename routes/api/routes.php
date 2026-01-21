<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/** Home */
Route::get('/', HomeController::class)->name('home');

/** V1 */
Route::prefix('v1')->name('v1.')->group(base_path(path: 'routes/api/v1/routes.php'));
