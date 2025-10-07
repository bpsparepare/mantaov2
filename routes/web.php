<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\InternalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;

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

    Route::post('/upload-file', [SubmissionController::class, 'uploadFile'])->name('file.upload');

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

// RUTE SEMENTARA UNTUK OTORISASI GOOGLE DRIVE
Route::get('/google-auth/generate-token', function () {
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/oauth_credentials.json'));
    $client->addScope(GoogleDrive::DRIVE);
    $client->setRedirectUri(route('google.callback'));
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    return redirect()->to($client->createAuthUrl());
})->name('google.auth');

Route::get('/google/callback', function (Request $request) {
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/oauth_credentials.json'));
    $client->setRedirectUri(route('google.callback'));

    $token = $client->fetchAccessTokenWithAuthCode($request->code);

    Storage::disk('local')->put('gdrive_token.json', json_encode($token));

    return 'Token berhasil dibuat! File <strong>gdrive_token.json</strong> telah disimpan di folder <strong>storage/app/</strong>. Anda bisa menutup halaman ini.';
})->name('google.callback');
