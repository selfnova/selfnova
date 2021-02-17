<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserFollow;

class FollowController extends Controller
{

	public function follow( Request $request )
	{
		$follow = UserFollow::where('u_id', Auth::id() )
			->where( 'to_id', $request->get('id') )
			->first();

		if ( !$follow )
		{
			$follow = new UserFollow;

			$follow->to_id = $request->get('id');
			$follow->u_id = Auth::id();

			$follow->save();

			User::where('id', Auth::id())->increment('followings');
			User::where('id', $request->get('id'))->increment('followers');

			$response = ['success' => true];

		} else {

			$follow->delete();

			User::where('id', Auth::id())->decrement('followings');
			User::where('id', $request->get('id'))->decrement('followers');

			$response = ['success' => true];
		}

		return response()->json( $response ) ?: '{}';
	}

	public function followers()
	{
		$followings = User::whereIn('users.id', function( $query )
			{
				$query->select('u_id')
					->from( with( new UserFollow )->getTable() )
					->where('to_id', Auth::user()->id );
			})
			->isFollow()
			->latest()
			->get();

		return response()->json( $followings ) ?: '{}';
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
}
