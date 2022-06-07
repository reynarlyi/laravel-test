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
Route::get('en/signin', [\App\Http\Controllers\Controller::class, 'index'])->name('login');
Route::post('post-login', [\App\Http\Controllers\Controller::class, 'postLogin'])->name('login.post');
Route::get('en/signup', [\App\Http\Controllers\Controller::class, 'registration'])->name('register');
Route::post('post-registration', [\App\Http\Controllers\Controller::class, 'postRegistration'])->name('register.post');
Route::get('en/dashboard', [\App\Http\Controllers\Controller::class, 'dashboard'])->name('dashboard');
Route::get('logout', [\App\Http\Controllers\Controller::class, 'logout'])->name('logout');
