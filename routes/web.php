<?php

use App\Http\Controllers\PlaygroundController;
use Illuminate\Support\Facades\Route;


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->name('vueApp');

if (app()->environment('local')) {
    Route::get('/playground', PlaygroundController::class);
}
