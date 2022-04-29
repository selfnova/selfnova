<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Repost;

use App\Events\Posted;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id)
    {
        $data = Post::where('posts.u_id', $user_id)
			->whereNull('posts.g_id')
			->leftJoin('posts as reposts', function ($join) use ($request) {
				$join->on('posts.id', '=', 'reposts.repost_id')
					->where('reposts.u_id', $request->user()->id);
			})
			->select('posts.*', 'reposts.id as reposted')
			->with('repost', 'user:id,name,last_name,avatar', 'comments')
			->latest()
			->get();

		return response()->json( $data ) ?: '{}';
    }

	public function groupPosts(Request $request, $group_id)
    {
        $data = Post::where('posts.g_id', $group_id)
			->leftJoin('posts as reposts', function ($join) use ($request) {
				$join->on('posts.id', '=', 'reposts.repost_id')
					->where('reposts.u_id', $request->user()->id);
			})
			->select('posts.*', 'reposts.id as reposted')
			->with('group:id,name,avatar', 'comments')
			->latest()
			->get();

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
		$create_data = [
			'u_id' => $request->user()->id,
			'g_id' => $request->get('g_id'),
			'subject' => $request->get('subject'),
			'text' => $request->get('text'),
			'video' => $request->get('video'),
			'music' => $request->get('music'),
		];

		if ( $request->file('image') )
			$create_data['photos'] =
			[
				$request->file('image')->store(
					'posts/attaches', 'public'
				)
			];

		$post = Post::create($create_data);

		$data = Post::with('user:id,name,last_name,avatar', 'comments')->find( $post->id );

		broadcast(new Posted($post) );

		return response()->json( $data ) ?: '{}';
    }

	public function repost(Request $request)
    {
		$u_id = $request->user()->id;

		$check = Post::select('u_id')
			->where('u_id', $u_id)
			->where('repost_id', $request->get('post_id'))
			->first();

		if ( $check ) return response()->json( ['success' => false] );

		$data = [
			'u_id' => $u_id,
			'repost_id' => $request->get('post_id'),
		];

		$post = Post::create($data);

		return response()->json( ['success' => true] ) ?: '{}';
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
		$post = Post::find( $id );

		if ( $post->u_id != $request->user()->id ) return false;

		$post->subject = $request->get('subject');
		$post->text = $request->get('text');

		if ( $request->get('video') ) {
			$post->photos = null;
			$post->music = null;
			$post->video = $request->get('video');
		}

		if ( $request->get('music') ) {
			$post->photos = null;
			$post->music = $request->get('music');
			$post->video = null;
		}

		if ( $request->file('image') ) {
			$post->music = null;
			$post->video = null;
			$post->photos = [
				$request->file('image')->store(
					'posts/attaches', 'public'
				)
			];
		}

		if ( $request->get('reset_attach') ) {
			$post->music = null;
			$post->video = null;
			$post->photos = null;
		}

		$post->save();

		return response()->json( ['success' => true] ) ?: '{}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);

		if ( $post->u_id != $request->user()->id ) return false;

		$post->delete();

		return response()->json( ['success' => true ] ) ?: '{}';
    }
}
