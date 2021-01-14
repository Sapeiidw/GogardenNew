<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::resource('user', UserController::class);
    Route::resource('category', CategoryController::class);
    Route::group(['prefix' => '/plant'], function () {       
        Route::get('/', [PlantController::class, 'index'])->name('plant.index');
        Route::post('/', [PlantController::class, 'store'])->name('plant.store');
        Route::get('/{plant}/edit', [PlantController::class, 'edit'])->name('plant.edit');
        Route::put('/{plant}', [PlantController::class, 'update'])->name('plant.update');    
        Route::delete('/{plant}', [PlantController::class, 'destroy'])->name('plant.destroy');    
        
        Route::get('/trashed/restore-all', [PlantController::class, 'restoreAllTrashed'])->name('plant.restoreAll');
        Route::delete('/trashed/delete-all', [PlantController::class, 'deleteAllTrashed'])->name('plant.deleteAll');

        Route::get('/trashed', [PlantController::class, 'fetchOnlyTrashed'])->name('plant.trashed');
        Route::put('/trashed/{plant}', [PlantController::class, 'restoreOnlyTrashed'])->name('plant.restore');
        Route::delete('/trashed/{plant}', [PlantController::class, 'deleteOnlyTrashed'])->name('plant.banish');
    });
});
