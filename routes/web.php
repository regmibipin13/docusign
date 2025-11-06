<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes - Laravel UI
Auth::routes();

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Password Management
    Route::get('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Two-Factor Authentication
    Route::get('/profile/two-factor', [App\Http\Controllers\Admin\ProfileController::class, 'twoFactor'])->name('profile.two-factor');
    Route::post('/profile/two-factor/enable', [App\Http\Controllers\Admin\ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
    Route::delete('/profile/two-factor/disable', [App\Http\Controllers\Admin\ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
    Route::get('/profile/recovery-codes', [App\Http\Controllers\Admin\ProfileController::class, 'showRecoveryCodes'])->name('profile.recovery-codes');
    Route::post('/profile/recovery-codes/regenerate', [App\Http\Controllers\Admin\ProfileController::class, 'regenerateRecoveryCodes'])->name('profile.recovery-codes.regenerate');

    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Document Management
    Route::resource('documents', App\Http\Controllers\Admin\DocumentController::class)->only(['index', 'show', 'destroy']);
    Route::get('/documents/{document}/download', [App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/view', [App\Http\Controllers\Admin\DocumentController::class, 'view'])->name('documents.view');

    // Signature Management
    Route::resource('signatures', App\Http\Controllers\Admin\SignatureController::class)->only(['index', 'show', 'destroy']);
    Route::post('/signatures/{signature}/toggle-status', [App\Http\Controllers\Admin\SignatureController::class, 'toggleStatus'])->name('signatures.toggle-status');

    // Add more admin routes here
    // Route::resource('settings', SettingController::class);
});

// Customer Routes
Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');

    // Password Management
    Route::get('/profile/password', [App\Http\Controllers\Customer\ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Two-Factor Authentication
    Route::get('/profile/two-factor', [App\Http\Controllers\Customer\ProfileController::class, 'twoFactor'])->name('profile.two-factor');
    Route::post('/profile/two-factor/enable', [App\Http\Controllers\Customer\ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
    Route::delete('/profile/two-factor/disable', [App\Http\Controllers\Customer\ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
    Route::get('/profile/recovery-codes', [App\Http\Controllers\Customer\ProfileController::class, 'showRecoveryCodes'])->name('profile.recovery-codes');
    Route::post('/profile/recovery-codes/regenerate', [App\Http\Controllers\Customer\ProfileController::class, 'regenerateRecoveryCodes'])->name('profile.recovery-codes.regenerate');

    // Document Management
    Route::resource('documents', App\Http\Controllers\Customer\DocumentController::class);
    Route::get('/documents/{document}/download', [App\Http\Controllers\Customer\DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/view', [App\Http\Controllers\Customer\DocumentController::class, 'view'])->name('documents.view');

    // Document Signing
    Route::get('/documents/{document}/sign', [App\Http\Controllers\Customer\DocumentSigningController::class, 'sign'])->name('documents.sign');
    Route::get('/signing/signatures', [App\Http\Controllers\Customer\DocumentSigningController::class, 'getSignatures'])->name('signing.signatures');
    Route::post('/documents/{document}/sign', [App\Http\Controllers\Customer\DocumentSigningController::class, 'store'])->name('documents.sign.store');

    // Signature Management
    Route::resource('signatures', App\Http\Controllers\Customer\SignatureController::class);
    Route::post('/signatures/{signature}/toggle-status', [App\Http\Controllers\Customer\SignatureController::class, 'toggleStatus'])->name('signatures.toggle-status');

    // Add more customer routes here
    // Route::resource('profile', ProfileController::class);
});

// Home route - redirects to role-based dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
