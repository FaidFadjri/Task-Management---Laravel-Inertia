<?php

use App\Controllers\Project;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Models\ProjectModels;
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
Route::get('logout', [AuthController::class, '_logout'])->name('logout');

#--- Dashboard Pages
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('authenticated');


#--- Project Pages
Route::prefix('project')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('project')->middleware('authenticated');

    #--- Project request
    Route::get('account', [ProjectController::class, '_getAccount']);
    Route::post('add', [ProjectController::class, '_addProject']);
    Route::post('update', [ProjectController::class, '_updateProject'])->name('update');
    Route::delete('delete/{project_id}', [ProjectController::class, '_deleteProject'])->name('delete');
    Route::post('comment', [ProjectController::class, '_addComment'])->name('comment');
    Route::post('save_user', [ProjectController::class, '_saveUser']);
});

Route::post('detail', [ProjectController::class, '_detailProject'])->middleware('authenticated');
Route::get('search', [ProjectController::class, 'search'])->name('search')->middleware('authenticated');
