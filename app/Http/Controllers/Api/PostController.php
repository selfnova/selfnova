<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Events\Posted;

class PostController extends Controller
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
			'text' => $request->get('text')
		];

		if ( $request->file('image') )
			$create_data['photos'] =
			[
				$request->file('image')->store(
					'avatars/'.$request->user()->id
				)
			];

		$post = Post::create($create_data);

		$data['post'] = Post::with('repost', 'user:id,name,avatar')->find( $post->id );

		broadcast(new Posted($post) );

		return response()->json( $data ) ?: '{}';
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
