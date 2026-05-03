<?php

use App\Http\Controllers\PlayController;
use App\Http\Controllers\LineupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//PlayController
Route::get('/play', [PlayController::class, 'index'])->name('play.index');
Route::post('/play/save', [PlayController::class, 'store'])->name('play.store');

//LineupController
Route::get('/lineups', [LineupController::class, 'index'])->name('lineups.index');
Route::get('/lineups/{lineup}/edit', [LineupController::class, 'edit'])->name('lineups.edit');
Route::get('/lineups/{lineup}', [LineupController::class, 'show'])->name('lineups.show');
Route::put('/lineups/{lineup}', [LineupController::class, 'update'])->name('lineups.update');
Route::delete('/lineups/{lineup}', [LineupController::class, 'destroy'])->name('lineups.destroy');