<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Comments;

class NewsController extends Controller
{
    public function __construct()
    {
  		$this->middleware('auth:api');
    }

    public function index( Request $request )
    {
		$news = News::latest()->simplePaginate();

		return response()->json( $news ) ?: '{}';
    }

	public function comments( Request $request, $id )
    {
		$news = Comments::where('type', 'news')
			->where('type_id', $id)
			->whereNull('reply_id')
			->with('user:id,name,last_name,avatar', 'replys')
			->latest()
			->simplePaginate(10);

		return response()->json( $news ) ?: '{}';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $alias )
    {
        $news = News::where('alias', $alias)->first();

		return response()->json( $news ) ?: '{}';
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
        $order = Order::find($id);

		// if ( $order->u_id != $request->user()->id ) return false;

		$order->delete();

		return response()->json( ['success' => true ] ) ?: '{}';
    }
}
