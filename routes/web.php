<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/agenda', [AgendaController::class, 'index']);

Route::get('/agenda/{id}', [AgendaController::class, 'detail']);