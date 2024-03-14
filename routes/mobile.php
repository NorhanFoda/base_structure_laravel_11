<?php


use App\Http\Controllers\Api\V1\Mobile\Auth\LoginController;
use App\Http\Controllers\Api\V1\Mobile\OrderController;

Route::post('login', LoginController::class);

Route::group(['middleware' => 'auth:sanctum'], static function () {
    
});
