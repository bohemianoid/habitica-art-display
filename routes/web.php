<?php

use Illuminate\Support\Facades\Route;

Route::view("/", "welcome")
    ->middleware(["auth", "verified", "ready"])
    ->name("display");

Route::view("dashboard", "dashboard")
    ->middleware(["auth", "verified", "ready"])
    ->name("dashboard");

Route::view("profile", "profile")
    ->middleware(["auth"])
    ->name("profile");

require __DIR__ . "/auth.php";
