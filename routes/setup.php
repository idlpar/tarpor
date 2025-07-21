<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupController;

Route::get('/setup/storage-link', [SetupController::class, 'linkStorage'])->name('storage.link');
