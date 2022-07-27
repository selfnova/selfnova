<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
  		$this->middleware('auth:api', ['except' => ['show']]);
    }

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

		if ( $request->file('avatar') ) {
			$path = $request->file('avatar')->store('group/avatars', 'public');
			$data['avatar'] = $path;
		}


		$group = Group::create($data);

		$follow = new GroupFollow;

		$follow->u_id  = Auth::id();
		$follow->to_id = $group->id;

		$follow->save();

		User::where('id', Auth::id())->increment('following_groups');
		Group::where('id', $group->id)->increment('followers');

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
			->withTrashed()
			->isFollow()
			->firstOrFail();

		if ( $data->is_ban ) return response()->json( ['is_ban' => true ] ) ?: '{}';

		return response()->json( $data ) ?: '{}';
    }

	public function recomended()
	{
		$recomended = Group::where('verify', 1)
			->isFollow()
			->inRandomOrder()
			->limit(4)
			->get();

		return $recomended;
	}

	public function uploadAvatar( Request $request, $id )
    {
		$request->validate([
		   'avatar' => 'mimetypes:image/jpeg,image/png',
		]);

		$path = $request->file('avatar')->store('group/avatars', 'public');

		$group = Group::find($id);
		$group->avatar = $path;
		$group->save();

		return response()->json( ['success' => true] ) ?: '{}';
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
	public function restore($group_id)
    {
        $user = Group::withTrashed()->find($group_id)->restore();

		return response()->json( ['success' => true] ) ?: '{}';
    }

    public function destroy($group_id)
    {
        $user = Group::find($group_id)->delete();

		return response()->json( ['success' => true] ) ?: '{}';
    }
}
