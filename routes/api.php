<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PostUserController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ViewController;

use App\Models\User;
use App\Models\Group;
use App\Models\City;
use Illuminate\Auth\Events\PasswordReset;

date_default_timezone_set('Europe/Moscow');

Route::group(['prefix' => 'v1'], function () {

	//===== Public / Half public API =====
	Route::get('public/users/{id}', [UserController::class, 'show']);
	Route::apiResource('users.posts', \PostUserController::class)->shallow();
	Route::apiResource('users.followers', \FollowerController::class);
	Route::get('users/{userId}/followings', [FollowerController::class, 'indexFollowings']);
	Route::get('users/{userId}/followingGroups', [FollowerController::class, 'indexFollowingGroups']);

	Route::get('public/groups/{id}', [GroupController::class, 'show']);
	Route::apiResource('groups.posts', \PostGroupController::class)->shallow();
	Route::apiResource('groups.products', \ProductController::class)->shallow();
	Route::apiResource('groups.reviews', \ReviewsController::class)->shallow();
	Route::apiResource('groups.orders', \OrderController::class)->shallow();
	Route::get('groups/{groupId}/followers', [FollowerController::class, 'groupsFollower']);

	Route::apiResource('comment', \CommentController::class);
	Route::apiResource('posts.comments', \CommentController::class);
	Route::get('news', [NewsController::class, 'index']);
	Route::get('news/{alias}', [NewsController::class, 'show']);
	Route::get('news/{id}/comments', [NewsController::class, 'comments']);

	Route::get('alias/{alias}', function($alias) {
		$user = User::select('id')->where( 'alias', $alias )->first();

		if ( $user ) $link = '/user/'.$user->id;
		else {
			$group = Group::select('id')->where( 'alias', $alias )->first();

			if ( $group ) $link = '/groups/'.$group->id;
			else return false;
		}

		return response()->json( ['link' => $link] ) ?: '{}';
	});

	Route::get('cities', function() {
		$data = City::orderBy('name')->get();

		return response()->json( $data ) ?: '{}';
	});

	//===== Private API =====

	Route::middleware(['auth:api', 'ban', 'verified'])->group(function () {
		Route::get('me', [UserController::class, 'me'])->name('me');
		Route::delete('me', [UserController::class, 'meDelete']);
		Route::get('board', [BoardController::class, 'index'])->name('board');
		Route::get('searchCity', [BoardController::class, 'searchCity']);
		Route::post('support', [SupportController::class, 'store']);
		Route::post('complains', [SupportController::class, 'report']);

		Route::apiResource('widgets', WidgetController::class);

		Route::post('users/avatar', [UserController::class, 'uploadAvatar']);
		Route::get('users/followers',[UserController::class, 'followers']);
		Route::get('users/followings',[UserController::class, 'followings']);
		Route::get('users/{user_id}/subscribe', [UserController::class, 'subscribe']);
		Route::get('users/followings/group',[UserController::class, 'followingsGroup']);
		Route::apiResource('users', \UserController::class);

		Route::post('groups/{id}/avatar', [GroupController::class, 'uploadAvatar']);
		Route::get('groups/{group_id}/subscribe', [GroupController::class, 'subscribe']);
		Route::get('groups/{group_id}/restore', [GroupController::class, 'restore']);
		Route::post('groups/search', [GroupController::class, 'search']);
		Route::apiResource('groups', \GroupController::class);

		Route::post('post/repost', [PostUserController::class, 'repost']);

		Route::post('peoples/search', [PeopleController::class, 'search']);
		Route::get('search', [BoardController::class, 'search']);
		Route::apiResource('peoples', \PeopleController::class);
		Route::apiResource('notifications', \NotificationController::class);

		Route::apiResource('messages', MessageController::class);
		Route::apiResource('chats', ChatController::class);
		Route::post('views/mark', [ViewController::class, 'mark']);
	});

	//===== Auth API =====

	Route::group(['namespace' => 'Auth'], function () {
		Route::post('register', RegisterController::class)->name('register');
		Route::post('login', LoginController::class)->name('login');

		Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
			->middleware(['signed', 'throttle:6,1'])
			->name('verification.verify');

		Route::post('/forgot-password', function (Request $request) {
			$request->validate(['email' => 'required|email']);

			$status = Password::sendResetLink(
				$request->only('email')
			);

			return $status === Password::RESET_LINK_SENT
					? response()->json( ['success' => true] )
					: response()->json( ['success' => false] );
		})->name('password.email');

		Route::get('/reset-password/{token}', function (Request $request, $token) {
			return redirect( config('app.front_url').'/reset_password?token='.$token.'&email='.$request->get('email') );
		})->middleware('guest')->name('password.reset');

		Route::post('/reset-password', function (Request $request) {
			$request->validate([
				'token' => 'required',
				'email' => 'required|email',
				'password' => 'required|min:6|confirmed',
			]);

			$status = Password::reset(
				$request->only('email', 'password', 'password_confirmation', 'token'),
				function ($user, $password) {
					$user->forceFill([
						'password' => Hash::make($password)
					])->setRememberToken(Str::random(60));

					$user->save();

					event(new PasswordReset($user));
				}
			);

			return $status === Password::PASSWORD_RESET
				? response()->json( ['success' => true] )
				: response()->json( ['success' => false] );
		})->middleware('guest')->name('password.update');
		// Route::post('logout', 'LogoutController')->middleware('auth:api');
	});
});






