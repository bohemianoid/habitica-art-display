<?php

use App\Http\Controllers\GenerateWebAppManifest;
use Illuminate\Support\Facades\Route;

Route::get('manifest.json', GenerateWebAppManifest::class);

Route::view('/', 'display')
    ->middleware(['auth', 'verified', 'ready'])
    ->name('display');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'ready'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::webhooks('habitica');

require __DIR__.'/auth.php';
