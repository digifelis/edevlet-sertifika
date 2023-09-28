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

    /* ogrenciler */
    Route::get('/ogrenciler', [App\Http\Controllers\Superadmin\OgrencilerController::class, 'index'])->name('superadmin.ogrenciler.index');
    Route::get('/ogrenciler/add', [App\Http\Controllers\Superadmin\OgrencilerController::class, 'create'])->name('superadmin.ogrenciler.create');
    Route::get('/ogrenciler/delete/{id}', [App\Http\Controllers\Superadmin\OgrencilerController::class, 'destroy'])->name('superadmin.ogrenciler.destroy');
    Route::get('/ogrenciler/edit/{id}', [App\Http\Controllers\Superadmin\OgrencilerController::class, 'edit'])->name('superadmin.ogrenciler.edit');

    Route::post('/ogrenciler/edit/{id}', [App\Http\Controllers\Superadmin\OgrencilerController::class, 'update'])->name('superadmin.ogrenciler.update');
    Route::post('/ogrenciler/add', [App\Http\Controllers\Superadmin\OgrencilerController::class, 'store'])->name('superadmin.ogrenciler.store');

    /* sertifikalar */
    Route::get('/sertifikalar', [App\Http\Controllers\Superadmin\SertifikalarController::class, 'index'])->name('superadmin.sertifikalar.index');
    Route::get('/sertifikalar/add', [App\Http\Controllers\Superadmin\SertifikalarController::class, 'create'])->name('superadmin.sertifikalar.create');
    Route::get('/sertifikalar/delete/{id}', [App\Http\Controllers\Superadmin\SertifikalarController::class, 'destroy'])->name('superadmin.sertifikalar.destroy');
    Route::get('/sertifikalar/edit/{id}', [App\Http\Controllers\Superadmin\SertifikalarController::class, 'edit'])->name('superadmin.sertifikalar.edit');

    Route::post('/sertifikalar/edit/{id}', [App\Http\Controllers\Superadmin\SertifikalarController::class, 'update'])->name('superadmin.sertifikalar.update');
    Route::post('/sertifikalar/add', [App\Http\Controllers\Superadmin\SertifikalarController::class, 'store'])->name('superadmin.sertifikalar.store');

    /* sinavlar */


});

Route::middleware(['auth','admin'])->prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

    /* kurs */
    Route::get('/kurslar', [App\Http\Controllers\Admin\KurslarController::class, 'index'])->name('admin.kurslar.index');
    Route::get('/kurslar/add', [App\Http\Controllers\Admin\KurslarController::class, 'create'])->name('admin.kurslar.create');
    Route::get('/kurslar/delete/{id}', [App\Http\Controllers\Admin\KurslarController::class, 'destroy'])->name('admin.kurslar.destroy');
    Route::get('/kurslar/edit/{id}', [App\Http\Controllers\Admin\KurslarController::class, 'edit'])->name('admin.kurslar.edit');

    Route::post('/kurslar/add', [App\Http\Controllers\Admin\KurslarController::class, 'store'])->name('admin.kurslar.store');
    Route::post('/kurslar/edit/{id}', [App\Http\Controllers\Admin\KurslarController::class, 'update'])->name('admin.kurslar.update');

    /* ogrenciler */
    Route::get('/ogrenciler', [App\Http\Controllers\Admin\OgrencilerController::class, 'index'])->name('admin.ogrenciler.index');
    Route::get('/ogrenciler/add', [App\Http\Controllers\Admin\OgrencilerController::class, 'create'])->name('admin.ogrenciler.create');
    Route::get('/ogrenciler/delete/{id}', [App\Http\Controllers\Admin\OgrencilerController::class, 'destroy'])->name('admin.ogrenciler.destroy');
    Route::get('/ogrenciler/edit/{id}', [App\Http\Controllers\Admin\OgrencilerController::class, 'edit'])->name('admin.ogrenciler.edit');

    Route::post('/ogrenciler/add', [App\Http\Controllers\Admin\OgrencilerController::class, 'store'])->name('admin.ogrenciler.store');
    Route::post('/ogrenciler/edit/{id}', [App\Http\Controllers\Admin\OgrencilerController::class, 'update'])->name('admin.ogrenciler.update');

    /* sertifikalar */
    Route::get('/sertifikalar', [App\Http\Controllers\Admin\SertifikalarController::class, 'index'])->name('admin.sertifikalar.index');
    Route::get('/sertifikalar/add', [App\Http\Controllers\Admin\SertifikalarController::class, 'create'])->name('admin.sertifikalar.create');
    Route::get('/sertifikalar/delete/{id}', [App\Http\Controllers\Admin\SertifikalarController::class, 'destroy'])->name('admin.sertifikalar.destroy');
    Route::get('/sertifikalar/edit/{id}', [App\Http\Controllers\Admin\SertifikalarController::class, 'edit'])->name('admin.sertifikalar.edit');

    Route::post('/sertifikalar/add', [App\Http\Controllers\Admin\SertifikalarController::class, 'store'])->name('admin.sertifikalar.store');
    Route::post('/sertifikalar/edit/{id}', [App\Http\Controllers\Admin\SertifikalarController::class, 'update'])->name('admin.sertifikalar.update');
    
});

Route::middleware(['auth','user'])->group(function () {
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.dashboard');
});


