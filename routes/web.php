<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\InternalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama akan langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Grup Rute Autentikasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'handleLogin']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// DITAMBAHKAN: Grup ini sekarang dijaga oleh middleware 'admin'
Route::prefix('admin')->middleware(['auth.custom', 'admin'])->group(function () {
    Route::get('/create-user', [AdminController::class, 'showCreateUserForm'])->name('admin.user.create');
    Route::post('/create-user', [AdminController::class, 'createUser']);
});

// Grup Rute Aplikasi Utama (setelah login)
Route::middleware('auth.custom')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

    // DITAMBAHKAN: Rute ini dijaga oleh middleware kepemilikan
    Route::get('/form/{slugInstansi}', [FormController::class, 'showForm'])
        ->name('form.show')
        ->middleware('check.instansi');

    Route::post('/submit-data', [SubmissionController::class, 'store'])->name('data.store');

    Route::get('/preview-data/{slugInstansi}', [FormController::class, 'fetchPreviewData'])->name('preview.data');

    // DITAMBAHKAN: Rute ini dijaga oleh middleware 'admin'
    Route::get('/internal', [InternalController::class, 'index'])
        ->name('internal.index')
        ->middleware('admin');

    // DITAMBAHKAN: Rute ini dijaga oleh middleware 'admin'
    Route::post('/internal/create-user', [InternalController::class, 'createUser'])
        ->name('internal.user.create')
        ->middleware('admin');
});
