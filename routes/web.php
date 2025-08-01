<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    Route::post('/berita/upload', [ProfileController::class, 'uploadBerita'])->name('berita.upload');
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/kategori/{kategori}', [HomeController::class, 'kategori'])->name('kategori.show');
Route::get('/kategori', function () {
    return view('list_kategori');
})->name('kategori.index');
Route::get('/kategori/{kategori}', [HomeController::class, 'kategori'])->name('berita.kategori');
Route::get('/kategori/{kategori}', [App\Http\Controllers\HomeController::class, 'kategori'])->name('kategori.show');

Route::get('/history', [HomeController::class, 'allHistory'])->name('history.all');

Route::post('/berita/{id}/like', [HomeController::class, 'like'])->name('berita.like')->middleware('auth');
Route::post('/berita/{id}/comment', [HomeController::class, 'comment'])->name('berita.comment')->middleware('auth');

Route::get('/berita/{id}', [HomeController::class, 'detail'])->name('berita.detail');
Route::get('/berita/{id}', [App\Http\Controllers\HomeController::class, 'detail'])->name('berita.detail');

Route::post('/berita/upload', [ProfileController::class, 'uploadBerita'])->name('berita.upload');