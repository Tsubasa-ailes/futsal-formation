<?php

use App\Http\Controllers\PlayController;
use App\Http\Controllers\LineupController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    //PlayController
    Route::get('/play', [PlayController::class, 'index'])->name('play.index');
    Route::post('/play/save', [PlayController::class, 'store'])->name('play.store');

    //LineupController
    Route::get('/lineups', [LineupController::class, 'index'])->name('lineups.index');
    Route::get('lineups/trash', [LineupController::class, 'trash'])->name('lineups.trash');
    Route::patch('/lineups/{id}/restore', [LineupController::class, 'restore'])->name('lineups.restore');
    Route::delete('/lineups/{id}/force-delete', [LineupController::class, 'forceDelete'])->name('lineups.force-delete');
    Route::get('/lineups/{lineup}/edit', [LineupController::class, 'edit'])->name('lineups.edit');
    Route::get('/lineups/{lineup}', [LineupController::class, 'show'])->name('lineups.show');
    Route::put('/lineups/{lineup}', [LineupController::class, 'update'])->name('lineups.update');
    Route::delete('/lineups/{lineup}', [LineupController::class, 'destroy'])->name('lineups.destroy');
});
//AuthController
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
