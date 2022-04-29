<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\UserFollow;
use App\Models\Group;
use App\Models\GroupFollow;

class UserController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$data = User::where( 'users.id', $id )
			->isFollow()
			->first();

		return response()->json( $data ) ?: '{}';
    }

	public function me()
    {
		$data = Auth::user();

		return response()->json( $data ) ?: '{}';
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
					->where('to_id', Auth::user()->id );
			})
			->isFollow()
			->latest()
			->get();

		return response()->json( $followers ) ?: '{}';
	}

	public function followings()
	{
		$followings = User::whereIn('users.id', function( $query )
			{
				$query->select('to_id')
					->from( with( new UserFollow )->getTable() )
					->where('u_id', Auth::user()->id );
			})
			->isFollow()
			->latest()
			->get();

		return response()->json( $followings ) ?: '{}';
	}

	public function followingsGroup()
	{
		$followings = Group::whereIn('groups.id', function( $query )
			{
				$query->select('to_id')
					->from( with( new GroupFollow )->getTable() )
					->where('u_id', Auth::user()->id );
			})
			->isFollow()
			->latest()
			->get();

		return response()->json( $followings ) ?: '{}';
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
		$data = Auth::user();

		$path = $request->file('avatar')->store('user/avatars', 'public');

		$uaer = Auth::user();
		$uaer->avatar = $path;
		$uaer->save();

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
        //
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
