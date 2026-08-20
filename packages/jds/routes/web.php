<?php

use Illuminate\Support\Facades\Route;
use Jetcar\Jds\Http\Controllers\AssetController;

Route::get('/_jds-assets/{path}', AssetController::class)
    ->where('path', '.*')
    ->name('jds.assets');
