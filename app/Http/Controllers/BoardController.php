<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;
use App\Models\Post;
use App\Models\Widget;
use App\Models\User;
use App\Models\Group;

class BoardController extends \App\Http\Controllers\Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data['news'] = News::get();
		$data['following_posts'] = Post::followingPosts();
		$data['group_posts'] = Post::followingGroupPosts();
		$data['widgets'] = Widget::getAll();

		return response()->json( $data ) ?: '{}';
	}

	public function search(Request $request)
	{
		$data['users'] = User::whereRaw('CONCAT( LOWER(`name`), LOWER(`last_name`) ) REGEXP ?',
			[$request->get('q')])
			->isFollow()
			->get();
		$data['groups'] = Group::whereRaw(
				'LOWER(`name`) REGEXP ?',
				[$request->get('q')]
			)
			->isFollow()
			->get();
		$data['news'] = News::where('name', $request->get('q'))->get();
		$data['posts'] = Post::where('subject', $request->get('q'))
			->with('user:id,name,last_name,avatar', 'group:id,name,avatar', 'comments')->get();

		return response()->json( $data ) ?: '{}';
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
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
