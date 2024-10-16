<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

Route::fallback(function () {
    return view('error.404');
})->middleware('auth');


// user login route
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [DashboardController::class, 'Logout'])->middleware('auth')->name('logout');

//company profile update
Route::get('/companyProfile', [DashboardController::class, 'companyProfile'])->name('companyProfile');
Route::post('/update-companyProfile', [DashboardController::class, 'updatecompanyProfile'])->name('update.companyProfile');

//panel and dashboard route
Route::group(['prefix' => 'panel'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/{panel}', [DashboardController::class, 'panel'])->name('panel.access');
});

// user route
Route::get('/user', [UserController::class, 'create'])->name('user.create');
Route::post('/get-user', [UserController::class, 'index'])->name('user.index');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::post('/update-user', [UserController::class, 'update'])->name('user.update');
Route::post('/delete-user', [UserController::class, 'destroy'])->name('user.delete');

// unit route
Route::get('/unit', [UnitController::class, 'create'])->name('unit.create');
Route::post('/get-unit', [UnitController::class, 'index'])->name('unit.index');
Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
Route::post('/update-unit', [UnitController::class, 'update'])->name('unit.update');
Route::post('/delete-unit', [UnitController::class, 'destroy'])->name('unit.delete');