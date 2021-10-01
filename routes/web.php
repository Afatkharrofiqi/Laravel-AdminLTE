<?php

use App\Http\Controllers\Administrator\ManagementUser\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('administrator')->group(function () {
    Route::prefix('manage-user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('manage.user.index');
        Route::post('create', [UserController::class, 'create'])->name('manage.user.create');
        Route::put('update/{id}', [UserController::class, 'update'])->name('manage.user.update');
        Route::delete('delete/{id}', [UserController::class, 'delete'])->name('manage.user.delete');
    });
});
