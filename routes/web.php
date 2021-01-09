<?php

use App\Http\Controllers\PlantController;
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

Route::resource('/plant', PlantController::class, ['name' => 
    [
        'index' => 'plant.index',
    ]
]);

Route::group(['prefix' => '/permanent-delete'], function () {
    Route::delete('/plant/{id}', [PlantController::class, 'permanentDelete']);
});

Route::group(['prefix' => '/restore'], function () {
    Route::get('/plant/{id}', [PlantController::class, 'restore']);
});

Route::group(['prefix' => '/trashed'], function () {
    Route::get('/plant', [PlantController::class, 'trashed']);
});

