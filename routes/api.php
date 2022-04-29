<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PostController;

use App\Http\Controllers\FollowController;

Route::middleware('auth:api')->prefix('v1')->group(function () {

	Route::get('board', [BoardController::class, 'index'])->name('board');
	Route::get('me', [UserController::class, 'me'])->name('me');

	Route::post('support', [SupportController::class, 'store']);

	Route::apiResource('settings', SettingController::class);

	Route::post('user/avatar', [UserController::class, 'uploadAvatar']);
	Route::get('user/{user_id}/subscribe', [UserController::class, 'subscribe']);
	Route::get('user/{user_id}/posts', [PostController::class, 'index']);
	Route::get('user/followings',[UserController::class, 'followings']);
	Route::get('user/followings/group',[UserController::class, 'followingsGroup']);
	Route::get('user/followers',[UserController::class, 'followers']);
	Route::apiResource('user', \UserController::class);

	Route::get('groups/{group_id}/posts', [PostController::class, 'groupPosts']);
	Route::get('groups/{group_id}/subscribe', [GroupController::class, 'subscribe']);
	Route::post('groups/search', [GroupController::class, 'search']);

	Route::apiResource('groups', \GroupController::class);
	Route::apiResource('groups.products', \ProductController::class)->shallow();
	Route::apiResource('groups.reviews', \ReviewsController::class)->shallow();
	Route::apiResource('groups.orders', OrderController::class)->shallow();

	Route::apiResource('post', \PostController::class);
	Route::post('post/repost', [PostController::class, 'repost']);
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

Route::group(['namespace' => 'Auth', 'prefix' => 'v1'], function () {
	Route::post('register', RegisterController::class)->name('register');

	Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
		->middleware(['signed', 'throttle:6,1'])
		->name('verification.verify');

	Route::post('login', LoginController::class)->name('login');
	// Route::post('logout', 'LogoutController')->middleware('auth:api');
});
