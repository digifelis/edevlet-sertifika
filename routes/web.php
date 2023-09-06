<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth','superadmin'])->group(function () {
    Route::get('/superadmin', [App\Http\Controllers\SuperadminController::class, 'index'])->name('superadmin.dashboard');
});

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth','user'])->group(function () {
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.dashboard');
});


