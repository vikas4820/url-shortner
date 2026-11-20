<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\UserController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

RateLimiter::for('login', function ($request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('invitations', function ($request) {
    return Limit::perMinute(10)->by($request->ip());
});

RateLimiter::for('short-urls', function ($request) {
    return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::get('/accept/{token}', [InvitationController::class, 'accept'])->name('accept')->middleware('throttle:invitations');
        Route::post('/accept/{token}', [InvitationController::class, 'confirmAccept'])->name('confirm.accept')->middleware('throttle:invitations');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('invitations')->name('invitations.')->group(function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::get('/create', [InvitationController::class, 'create'])->name('create');
        Route::post('/', [InvitationController::class, 'store'])->name('store')->middleware('throttle:invitations');
        Route::get('/{invitation}', [InvitationController::class, 'show'])->name('show');
    });

    Route::prefix('short-urls')->name('short-urls.')->group(function () {
        Route::get('/', [ShortUrlController::class, 'index'])->name('index');
        Route::get('/create', [ShortUrlController::class, 'create'])->name('create');
        Route::post('/', [ShortUrlController::class, 'store'])->name('store')->middleware('throttle:short-urls');
        Route::get('/{shortUrl}', [ShortUrlController::class, 'show'])->name('show');
        Route::get('/r/{shortCode}', [ShortUrlController::class, 'redirect'])->name('redirect');
    });
});
