<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
  		$this->middleware('auth:api');
    }

    public function index( Request $request, $g_id )
    {
		$order = Order::where('g_id', $g_id)->with('product')->get();

		return response()->json( $order ) ?: '{}';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $g_id)
    {
        $create_data = [
			'u_id' => $request->user()->id,
			'p_id' => $request->get('p_id'),
			'g_id' => $g_id,
			'name' => $request->get('name'),
			'phone' => $request->get('phone'),
			'comment' => $request->get('comment'),
		];

		$order = Order::create($create_data);

		return response()->json( $order ) ?: '{}';
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
        $order = Order::find($id);

		// if ( $order->u_id != $request->user()->id ) return false;

		$order->delete();

		return response()->json( ['success' => true ] ) ?: '{}';
    }
}
