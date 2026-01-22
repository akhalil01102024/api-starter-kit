<?php

use Illuminate\Support\Facades\Route;

/** Auth */
Route::prefix('auth')->name('auth.')->group(base_path(path: 'routes/api/v1/auth.php'));
