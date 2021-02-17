<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\PeopleController;

use App\Http\Controllers\Api\FollowController;

Route::middleware('auth:api')->group(function () {

	Route::get('layout', [AppController::class, 'layout'])->name('layout');
	Route::get('board', [BoardController::class, 'index'])->name('board');

	Route::post('support', [SupportController::class, 'store']);

	Route::apiResource('settings', SettingController::class);
	Route::apiResource('user', UserController::class);
	Route::apiResource('post', PostController::class);
	Route::apiResource('comment', CommentController::class);
	Route::apiResource('widgets', WidgetController::class);

	Route::get('peoples/followings',[FollowController::class, 'followings']);
	Route::get('peoples/followers',[FollowController::class, 'followers']);
	Route::post('peoples/follow',[FollowController::class, 'follow']);

	Route::post('peoples/search', [PeopleController::class, 'search']);
	Route::apiResource('peoples', \PeopleController::class);

	Route::apiResource('messages', MessageController::class);
	Route::apiResource('chats', ChatController::class);
});

Route::group(['namespace' => 'Auth'], function () {
	// Route::post('register', 'RegisterController');
	Route::post('login', LoginController::class)->name('login');
	// Route::post('logout', 'LogoutController')->middleware('auth:api');
});
