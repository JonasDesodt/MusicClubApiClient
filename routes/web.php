<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::get('/set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'nl'])) { 
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('set-locale');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/agenda', [AgendaController::class, 'index']);

Route::get('/agenda/{id}', [AgendaController::class, 'detail']);