<?php

use App\Http\Controllers\PlaygroundController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
if (app()->environment('local')) {
    Route::get('/playground', PlaygroundController::class);
}
