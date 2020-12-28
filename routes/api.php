<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\BoardController;

use App\Http\Controllers\Api\SupportController;

Route::middleware('auth:api')->group(function () {

	Route::get('layout', [AppController::class, 'layout'])->name('layout');
	Route::get('board', [BoardController::class, 'index'])->name('board');

	Route::post('support', [SupportController::class, 'store']);

});

Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        // Route::post('register', 'RegisterController');
        Route::post('login', 'LoginController')->name('login');
        // Route::post('logout', 'LogoutController')->middleware('auth:api');
    });
});
