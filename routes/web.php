<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
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

Route::get('/users/login', [UserController::class, 'login'])->middleware(['auth']);

Route::get('/users/current', [UserController::class, 'current'])->middleware(['auth']);
// By default, middleware auth pake guard 'web' (di config/auth.php), sehingga data user disimpan di session

Route::get('/api/users/current', [UserController::class, 'current'])->middleware(['auth:token']);
// Pake custom guard (token) yg ada di AuthServiceProvider
/* Flow pengecekan token pada request header :
1. Custom guard di middleware 'auth' pada route ini adalah 'token'
2. Masuk ke 'Auth extend token' di AuthServiceProvider
4. Logic dari TokenGuard dijalanin, yaitu cek user berdasarkan field 'token' di db
  4.a. Kenapa yg dicek adalah tabel 'users'? karena provider utk guard 'token' adalah 'users', ini ada di config/auth.php
*/
Route::get('/simple-api/users/current', [UserController::class, 'current'])->middleware(['auth:simple-token']);

Route::post('/api/todos', [TodoController::class, 'create']);




// Bawaan
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
