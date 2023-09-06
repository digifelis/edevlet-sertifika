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
/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
*/
require __DIR__.'/auth.php';


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth','superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/', [App\Http\Controllers\SuperadminController::class, 'index'])->name('superadmin.dashboard');
    /* user */
    Route::get('/users', [App\Http\Controllers\Superadmin\UsersController::class, 'index'])->name('superadmin.users.index');
    Route::get('/users/add', [App\Http\Controllers\Superadmin\UsersController::class, 'add'])->name('superadmin.users.add');
    Route::get('/users/delete/{id}', [App\Http\Controllers\Superadmin\UsersController::class, 'delete'])->name('superadmin.users.delete');
    Route::get('/users/edit/{id}', [App\Http\Controllers\Superadmin\UsersController::class, 'edit'])->name('superadmin.users.edit');

    Route::post('/users/edit/{id}', [App\Http\Controllers\Superadmin\UsersController::class, 'update'])->name('superadmin.users.update');
    Route::post('/users/add', [App\Http\Controllers\Superadmin\UsersController::class, 'store'])->name('superadmin.users.store');

    /* kurum */
    Route::get('/kurumlar', [App\Http\Controllers\Superadmin\KurumlarController::class, 'index'])->name('superadmin.kurumlar.index');
    Route::get('/kurumlar/add', [App\Http\Controllers\Superadmin\KurumlarController::class, 'create'])->name('superadmin.kurumlar.create');
    Route::get('/kurumlar/delete/{id}', [App\Http\Controllers\Superadmin\KurumlarController::class, 'destroy'])->name('superadmin.kurumlar.destroy');
    Route::get('/kurumlar/edit/{id}', [App\Http\Controllers\Superadmin\KurumlarController::class, 'edit'])->name('superadmin.kurumlar.edit');

    Route::post('/kurumlar/edit/{id}', [App\Http\Controllers\Superadmin\KurumlarController::class, 'update'])->name('superadmin.kurumlar.update');
    Route::post('/kurumlar/add', [App\Http\Controllers\Superadmin\KurumlarController::class, 'store'])->name('superadmin.kurumlar.store');

    /* kurs */
    Route::get('/kurslar', [App\Http\Controllers\Superadmin\KurslarController::class, 'index'])->name('superadmin.kurslar.index');
    Route::get('/kurslar/add', [App\Http\Controllers\Superadmin\KurslarController::class, 'create'])->name('superadmin.kurslar.create');
    Route::get('/kurslar/delete/{id}', [App\Http\Controllers\Superadmin\KurslarController::class, 'destroy'])->name('superadmin.kurslar.destroy');
    Route::get('/kurslar/edit/{id}', [App\Http\Controllers\Superadmin\KurslarController::class, 'edit'])->name('superadmin.kurslar.edit');

    Route::post('/kurslar/edit/{id}', [App\Http\Controllers\Superadmin\KurslarController::class, 'update'])->name('superadmin.kurslar.update');
    Route::post('/kurslar/add', [App\Http\Controllers\Superadmin\KurslarController::class, 'store'])->name('superadmin.kurslar.store');
    




});

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth','user'])->group(function () {
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.dashboard');
});


