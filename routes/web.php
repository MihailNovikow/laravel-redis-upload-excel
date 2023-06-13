<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\RowsController;
use App\Http\Middleware\BasicAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('upload');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});
Route::prefix('/rows')->group(function () {
    Route::get('/', [RowsController::class, 'get'])->middleware(BasicAuth::class);
    Route::post('/parse', [RowsController::class, 'parse'])->name('rows.parse')->middleware(BasicAuth::class);
});
Route::post('/import', '\App\Http\Controllers\ExcelController@importData');

Route::get('/export', '\App\Http\Controllers\ExcelController@exportData');

require __DIR__.'/auth.php';
