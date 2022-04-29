<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $group_id)
    {
        $data = Product::where('g_id', $group_id)
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
			'price' => $request->get('price'),
			'currency' => $request->get('currency'),
		];

		if ( $request->file('image') )
			$create_data['photos'] =
			[
				$request->file('image')->store(
					'products', 'public'
				)
			];

		$post = Product::create($create_data);

		$data = Product::find( $post->id );

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
		$post = Product::find( $id );

		if ( $post->u_id != $request->user()->id ) return false;

		$post->subject = $request->get('subject');
		$post->text = $request->get('text');
		$post->price = $request->get('price');
		$post->currency = $request->get('currency');

		if ( $request->file('image') ) {
			$post->photos = [
				$request->file('image')->store(
					'products', 'public'
				)
			];
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
    public function destroy(Request $request, $product)
    {
        $prod = Product::find($product);

		if ( $prod->u_id != $request->user()->id ) return false;

		$prod->delete();

		return response()->json( ['success' => true ] ) ?: '{}';
    }
}
