<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

/* ---------- public ---------- */
Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/resume', [PortfolioController::class, 'resume'])->name('resume.download');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/* ---------- admin (simple session auth via ADMIN_PASSWORD in .env) ---------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'settingsSave'])->name('settings.save');
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::delete('/messages/{message}', [AdminController::class, 'messageDelete'])->name('messages.delete');
    Route::get('/visitors', [AdminController::class, 'visitors'])->name('visitors');

    Route::get('/{resource}', [AdminController::class, 'index'])->name('res.index');
    Route::get('/{resource}/create', [AdminController::class, 'create'])->name('res.create');
    Route::post('/{resource}', [AdminController::class, 'store'])->name('res.store');
    Route::get('/{resource}/{id}/edit', [AdminController::class, 'edit'])->name('res.edit');
    Route::put('/{resource}/{id}', [AdminController::class, 'update'])->name('res.update');
    Route::delete('/{resource}/{id}', [AdminController::class, 'destroy'])->name('res.destroy');
});
