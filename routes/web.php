<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\pages\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


#--- Authentication Routes
Route::get('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, '_storeLogin'])->name('login');


#--- Dashboard Pages
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');