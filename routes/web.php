<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return redirect('/login');
});

// ========== PROFILE ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ========== AUTHENTICATION ==========
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ========== DASHBOARD ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard berdasarkan role
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/warga', [DashboardController::class, 'warga'])->name('dashboard.warga');
});

// ASPIRASI ROUTES YANG BARU
Route::middleware(['auth'])->group(function () {
    // ASPIRASI - UNTUK WARGA
    Route::get('/aspirasi/buat', [AspirasiController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi/buat', [AspirasiController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi/saya', [AspirasiController::class, 'myAspirasi'])->name('aspirasi.my');
    Route::get('/aspirasi/semua', [AspirasiController::class, 'publicIndex'])->name('aspirasi.public');
    Route::get('/aspirasi/{id}', [AspirasiController::class, 'show'])->name('aspirasi.show');
    
    // ASPIRASI - UNTUK ADMIN (TANPA MIDDLEWARE 'admin')
    Route::get('/aspirasi/{id}/edit', [AspirasiController::class, 'edit'])->name('aspirasi.edit');
    Route::put('/aspirasi/{id}', [AspirasiController::class, 'update'])->name('aspirasi.update');
    
    Route::get('/admin/aspirasi', [AspirasiController::class, 'index'])->name('aspirasi.index');
    Route::post('/admin/aspirasi/{id}/approve', [AspirasiController::class, 'approve'])->name('aspirasi.approve');
    Route::post('/admin/aspirasi/{id}/reject', [AspirasiController::class, 'reject'])->name('aspirasi.reject');
    Route::post('/admin/aspirasi/{id}/respond', [AspirasiController::class, 'respond'])->name('aspirasi.respond');
    Route::post('/admin/aspirasi/{id}/progress', [AspirasiController::class, 'markInProgress'])->name('aspirasi.markInProgress');
    Route::post('/admin/aspirasi/{id}/complete', [AspirasiController::class, 'markCompleted'])->name('aspirasi.markCompleted');
    Route::delete('/admin/aspirasi/{id}', [AspirasiController::class, 'destroy'])->name('aspirasi.destroy');
});

// ========== VOTING ==========
Route::middleware(['auth'])->group(function () {
    // Admin voting
    Route::get('/voting/manage', [VotingController::class, 'manageSessions'])->name('voting.manage');
    Route::get('/voting/create-session', [VotingController::class, 'createSession'])->name('voting.createSession');
    Route::post('/voting/store-session', [VotingController::class, 'storeSession'])->name('voting.storeSession');
    Route::get('/voting/{id}/edit', [VotingController::class, 'editSession'])->name('voting.edit');
    Route::put('/voting/{id}/update', [VotingController::class, 'updateSession'])->name('voting.update');
    Route::delete('/voting/{id}', [VotingController::class, 'destroySession'])->name('voting.destroy');
    Route::post('/voting/{id}/close', [VotingController::class, 'closeSession'])->name('voting.close');

    // Public voting
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::get('/voting/{id}', [VotingController::class, 'show'])->name('voting.show');
    Route::get('/voting/{id}/results', [VotingController::class, 'results'])->name('voting.results');
    Route::get('/voting/{id}/vote', [VotingController::class, 'voteForm'])->name('voting.voteForm');
    Route::post('/voting/{session_id}/submit', [VotingController::class, 'submitVote'])->name('voting.submit');
});
// ========== USERS ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
});

// ========== FALLBACK ==========
Route::fallback(function () {
    return redirect('/dashboard');
});