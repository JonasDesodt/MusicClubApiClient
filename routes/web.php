<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ContactController;

// Define a route group with a locale parameter at the end
Route::group(['prefix' => '{locale}'/*, 'where' => ['locale' => 'en|nl']*/], function () {

    // Route::get('/', function () {
    //     return view('welcome');
    // });
    
    Route::get('/', [AgendaController::class, 'index']);

    Route::get('/about', [AboutController::class, 'index'])->name('about.index');


    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    
    Route::get('/agenda/{id}', [AgendaController::class, 'detail'])->where('id', '[0-9]+')->name('agenda.detail');


    Route::get('/contaxt', [ContactController::class, 'index'])->name('contact.index');
});

