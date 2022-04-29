<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
			'u_id' => $request->user()->id,
			'name' => $request->get('name'),
			'about' => $request->get('about'),
			'service' => $request->get('service'),
			'city' => $request->get('city'),
		];

		$path = $request->file('avatar')->store('group/avatars', 'public');

		$data['avatar'] = $path;

		$group = Group::create($data);

		return response()->json( ['success' => true, 'g_id' => $group->id] ) ?: '{}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Group::where( 'groups.id', $id )
			->isFollow()
			->first();

		return response()->json( $data ) ?: '{}';
    }

	public function subscribe( Request $request, $group_id )
	{
		$follow = GroupFollow::where('u_id', Auth::id() )
			->where( 'to_id', $group_id )
			->first();

		if ( !$follow )
		{
			$follow = new GroupFollow;

			$follow->u_id  = Auth::id();
			$follow->to_id = $group_id;

			$follow->save();

			User::where('id', Auth::id())->increment('following_groups');
			Group::where('id', $group_id)->increment('followers');

			$response = ['success' => true];

		} else {

			$follow->delete();

			User::where('id', Auth::id())->decrement('following_groups');
			Group::where('id', $group_id)->decrement('followers');

			$response = ['success' => true];
		}

		return response()->json( $response ) ?: '{}';
	}

	public function search(Request $request)
    {
		$data = Group::whereRaw(
				'LOWER(`name`) REGEXP ?',
				[$request->get('search')]
			)
			->isFollow()
			->get();

        return response()->json( $data ) ?: '{}';
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
		if ( $group->u_id != $request->user()->id ) return false;

		$group->name = $request->get('name');
		$group->country = $request->get('country');
		$group->city = $request->get('city');
		$group->about = $request->get('about');
		$group->phone = $request->get('phone');
		$group->service = $request->get('service');
		$group->address = $request->get('address');
		$group->site = $request->get('site');

		$group->save();

		return response()->json( $group ) ?: '{}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
