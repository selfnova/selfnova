<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $u_id)
    {
		$followers = User::whereIn('users.id', function ( $query ) use ($u_id)
			{
				$query->select('u_id')
					->from( 'user_follows' )
					->where('to_id', $u_id );
			})
			->latest();

		if ( $request->get('q') )
			$followers->whereRaw('CONCAT( LOWER(`name`), LOWER(`last_name`) ) REGEXP ?',
				[$request->get('q')]);

		$followers = $followers->simplePaginate(50);

		return response()->json( $followers ) ?: '{}';
    }

	public function groupsFollower(Request $request, $u_id)
    {
		$followers = User::whereIn('users.id', function ( $query ) use ($u_id)
			{
				$query->select('u_id')
					->from( 'group_follows' )
					->where('to_id', $u_id );
			})
			->latest();

		if ( $request->get('q') )
			$followers->whereRaw('CONCAT( LOWER(`name`), LOWER(`last_name`) ) REGEXP ?',
				[$request->get('q')]);

		$followers = $followers->simplePaginate(50);

		return response()->json( $followers ) ?: '{}';
    }

	public function indexFollowings(Request $request, $u_id)
    {
		$followers = User::whereIn('users.id', function ( $query ) use ($u_id)
			{
				$query->select('to_id')
					->from( 'user_follows' )
					->where('u_id', $u_id );
			})
			->latest();

		if ( $request->get('q') )
			$followers->whereRaw('CONCAT( LOWER(`name`), LOWER(`last_name`) ) REGEXP ?',
				[$request->get('q')]);

		$followers = $followers->simplePaginate(50);

		return response()->json( $followers ) ?: '{}';
    }

	public function indexFollowingGroups(Request $request, $u_id)
    {
		$followers = Group::whereIn('groups.id', function ( $query ) use ($u_id)
			{
				$query->select('to_id')
					->from( 'group_follows' )
					->where('u_id', $u_id );
			})
			->latest();

		if ( $request->get('q') )
			$followers->whereRaw('LOWER(`name`) REGEXP ?',
				[$request->get('q')]);

		$followers = $followers->simplePaginate(50);

		return response()->json( $followers ) ?: '{}';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function search(Request $request)
    {
		$data = User::whereRaw('CONCAT( LOWER(`name`), LOWER(`last_name`) ) REGEXP ?',
			[$request->get('search')])
			->isFollow()
			->get();

        return response()->json( $data ) ?: '{}';
	}

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
