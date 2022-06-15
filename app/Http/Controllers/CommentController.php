<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;

use App\Events\CommentAdd;

class CommentController extends Controller
{
	public function __construct()
    {
  		$this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index( $post_id )
    {
        return response()->json( Comments::where('type_id', $post_id)->with('user:id,name,last_name,avatar')->simplePaginate(4) ) ?: '{}';
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
			'type_id' => $request->get('type_id'),
			'reply_id' => $request->get('reply_id'),
			'text' => $this->replaceLink( $request->get('text') )
		];

		if ( $request->get('type') ) $create_data['type'] = $request->get('type');

		$comment = Comments::create($create_data);

		$data = Comments::with('user:id,name,last_name,avatar')->find( $comment->id );

		broadcast(new CommentAdd( $data ) )->toOthers();

		return response()->json( $data ) ?: '{}';
    }

	protected function replaceLink( $text )
	{
		$preg = '/((https|http):\/\/[a-zA-Z0-9-.\/]+)/';
		$link = '<a target="_blank" href="$1">$1</a>';

		return preg_replace($preg, $link, $text);
	}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function show(Comments $comments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comments $comments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comments::find($id);

		$comment->delete();

		return response()->json( ['success' => true ] ) ?: '{}';
    }
}
