<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\UserFollow;
use App\Models\Group;
use App\Models\ChatUser;
use App\Models\GroupFollow;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
  		$this->middleware('auth:api', ['except' => ['show']]);
    }

    public function index()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $r, $id)
    {
		$data = User::where( 'users.id', $id )
			->isFollow()
			->firstOrFail();

		if ( $data->is_ban ) return response()->json( ['is_ban' => true ] ) ?: '{}';

		return response()->json( $data ) ?: '{}';
    }

	public function me()
    {
		$data = User::withTrashed()->find(Auth::id());

		$data->unread_noty = Notification::forUser($data->id, true)->count();


		$data->unread_msg = 0;

		$chats = ChatUser::where('u_id', Auth::id())->get();

		foreach ( $chats as $value ) {
			$data->unread_msg += $value['unread_msg'];
		}

		return response()->json( $data ) ?: '{}';
    }

	public function meDelete()
    {
		$user = User::find(Auth::id())->delete();

		return response()->json( ['success' => true] ) ?: '{}';
    }

	public function subscribe( Request $request, $user_id )
	{
		$follow = UserFollow::where('u_id', Auth::id() )
			->where( 'to_id', $user_id )
			->first();

		if ( !$follow )
		{
			$follow = new UserFollow;

			$follow->to_id = $user_id;
			$follow->u_id = Auth::id();

			$follow->save();

			User::where('id', Auth::id())->increment('followings');
			User::where('id', $user_id)->increment('followers');

			$response = ['success' => true];

		} else {

			$follow->delete();

			User::where('id', Auth::id())->decrement('followings');
			User::where('id', $user_id)->decrement('followers');

			$response = ['success' => true];
		}

		return response()->json( $response ) ?: '{}';
	}

	public function followers()
	{
		$followers = User::whereIn('users.id', function( $query )
			{
				$query->select('u_id')
					->from( with( new UserFollow )->getTable() )
					->where('to_id', Auth::id() );
			})
			->isFollow()
			->latest()
			->simplePaginate(50);

		return response()->json( $followers ) ?: '{}';
	}

	public function followings()
	{
		$followings = User::whereIn('users.id', function( $query )
			{
				$query->select('to_id')
					->from( with( new UserFollow )->getTable() )
					->where('u_id', Auth::id() );
			})
			->isFollow()
			->latest()
			->simplePaginate(50);

		return response()->json( $followings ) ?: '{}';
	}

	public function followingsGroup()
	{
		$followings = Group::whereIn('groups.id', function( $query )
			{
				$query->select('to_id')
					->from( with( new GroupFollow )->getTable() )
					->where('u_id', Auth::id() );
			})
			->withTrashed()
			->isFollow()
			->latest()
			->get();

		return response()->json( $followings ) ?: '{}';
	}

	public function recomended()
	{
		$recomended = User::where('verify', 1)
			->isFollow()
			->inRandomOrder()
			->limit(4)
			->get();

		return $recomended;
	}

	public function search(Request $request)
    {
		$data['peoples'] = User::whereRaw('CONCAT( LOWER(`name`), LOWER(`last_name`) ) REGEXP ?',
			[$request->get('search')])
			->isFollow()
			->get();

        return response()->json( $data ) ?: '{}';
	}

	public function uploadAvatar( Request $request )
    {
		$request->validate([
		   'avatar' => 'mimetypes:image/jpeg,image/png',
		]);

		$path = $request->file('avatar')->store('user/avatars', 'public');

		$user = Auth::user();
		$user->avatar = $path;
		$user->save();

		return response()->json( ['success' => true] ) ?: '{}';
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

		$user->country = $request->get('country');
		$user->name = $request->get('name');
		$user->last_name = $request->get('last_name');
		$user->city = $request->get('city');
		$user->about = $request->get('about');
		$user->phone = $request->get('phone');
		$user->site = $request->get('site');
		$user->born = $request->get('born');
		$user->photoblog = $request->get('photoblog');
		$user->private_set = $request->get('private_set');

		if ( $request->get('password') ) {
			$user->password = Hash::make($request->get('password'));
		}

		$user->save();

		return response()->json( $user ) ?: '{}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
