<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\ProjectController;
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

#--- Authentication Routes
Route::get('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, '_storeLogin'])->name('login');

#--- Dashboard Pages
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('authenticated');



#--- Project Pages
Route::prefix('project')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('project')->middleware('authenticated');
    Route::get('/{showAll}', [ProjectController::class, 'index'])->name('projectShow')->middleware('authenticated');
});
