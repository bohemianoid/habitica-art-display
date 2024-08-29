<?php

use App\Http\Controllers\GenerateWebAppManifest;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('manifest.json', GenerateWebAppManifest::class);

Volt::route('/', 'display.show')
    ->middleware(['auth', 'verified', 'ready'])
    ->name('display');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'ready'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::webhooks('habitica', 'habitica');

require __DIR__.'/auth.php';
