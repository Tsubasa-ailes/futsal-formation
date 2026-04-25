<?php

use App\Http\Controllers\PlayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/play', [PlayController::class, 'index'])->name('play.index');
Route::post('/play/save', [PlayController::class, 'store'])->name('play.store');