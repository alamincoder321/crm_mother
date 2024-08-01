<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

Route::fallback(function () {
    return view('error.404');
})->middleware('auth');


// user login route
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [DashboardController::class, 'Logout'])->middleware('auth')->name('logout');

//panel and dashboard route
Route::group(['prefix' => 'panel'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/{panel}', [DashboardController::class, 'panel'])->name('panel.access');
});
