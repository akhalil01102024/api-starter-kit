<?php

use Illuminate\Support\Facades\Route;

/** V1 */
Route::prefix('v1')->name('v1.')->group(base_path(path: 'routes/api/v1/routes.php'));
