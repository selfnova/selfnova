<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewsController extends Controller
{
    public function __construct()
    {
  		$this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, $group_id)
    {
		$data = Review::where('g_id', $group_id)
			->with('user:id,name,last_name,avatar', 'comments')
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
		if (
			Review::where([
				['u_id', '=', $request->user()->id],
				['g_id', '=', $request->get('g_id')]
			])->first()
		) {
			return response()->json( ['exists' => true] ) ?: '{}';
		}
        $create_data = [
			'u_id' => $request->user()->id,
			'g_id' => $request->get('g_id'),
			'subject' => $request->get('subject'),
			'text' => $request->get('text'),
			'rating' => $request->get('rating'),
		];

		if ( $request->file('image') )
			$create_data['photos'] =
			[
				$request->file('image')->store(
					'reviews', 'public'
				)
			];

		$review = Review::create($create_data);

		$data = Review::with('user:id,name,last_name,avatar', 'comments')->find( $review->id );

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
    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);

		if ( $review->u_id != $request->user()->id ) return false;

		$review->delete();

		return response()->json( ['success' => true ] ) ?: '{}';
    }
}
