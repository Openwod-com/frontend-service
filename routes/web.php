<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\LoginController;
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

Route::get('/', [ActivityController::class, 'index'])->name('home')->middleware('auth');

Route::get('login', [LoginController::class, 'index'])->name('login');

Route::get('/about', function() {
    return view('about');
})->name('about');

Route::get('/policy', function() {
    return view('policy');
})->name('policy');

Route::get('/members', function() {
    return view('members');
})->name('members')->middleware('auth');
