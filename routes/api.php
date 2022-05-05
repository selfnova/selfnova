<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PostUserController;

Route::group(['prefix' => 'v1'], function () {

	//===== Public / Half public API =====
	Route::get('public/users/{id}', [UserController::class, 'show']);
	Route::apiResource('users.posts', \PostUserController::class)->shallow();

	Route::get('public/groups/{id}', [GroupController::class, 'show']);
	Route::apiResource('groups.posts', \PostGroupController::class)->shallow();
	Route::apiResource('groups.products', \ProductController::class)->shallow();
	Route::apiResource('groups.reviews', \ReviewsController::class)->shallow();
	Route::apiResource('groups.orders', \OrderController::class)->shallow();

	Route::apiResource('comment', \CommentController::class);

	//===== Private API =====

	Route::middleware(['auth:api', 'verified'])->group(function () {
		Route::get('me', [UserController::class, 'me'])->name('me');
		Route::get('board', [BoardController::class, 'index'])->name('board');
		Route::post('support', [SupportController::class, 'store']);

		Route::apiResource('widgets', WidgetController::class);

		Route::post('users/avatar', [UserController::class, 'uploadAvatar']);
		Route::get('users/followers',[UserController::class, 'followers']);
		Route::get('users/followings',[UserController::class, 'followings']);
		Route::get('users/{user_id}/subscribe', [UserController::class, 'subscribe']);
		Route::get('users/followings/group',[UserController::class, 'followingsGroup']);
		Route::apiResource('users', \UserController::class);

		Route::post('groups/{id}/avatar', [GroupController::class, 'uploadAvatar']);
		Route::get('groups/{group_id}/subscribe', [GroupController::class, 'subscribe']);
		Route::post('groups/search', [GroupController::class, 'search']);
		Route::apiResource('groups', \GroupController::class);

		Route::post('post/repost', [PostUserController::class, 'repost']);

		Route::post('peoples/search', [PeopleController::class, 'search']);
		Route::get('search', [BoardController::class, 'search']);
		Route::apiResource('peoples', \PeopleController::class);
		Route::apiResource('notifications', \NotificationController::class);

		Route::apiResource('messages', MessageController::class);
		Route::apiResource('chats', ChatController::class);
	});

	//===== Auth API =====

	Route::group(['namespace' => 'Auth'], function () {
		Route::post('register', RegisterController::class)->name('register');
		Route::post('login', LoginController::class)->name('login');

		Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
			->middleware(['signed', 'throttle:6,1'])
			->name('verification.verify');

		// Route::post('logout', 'LogoutController')->middleware('auth:api');
	});
});






