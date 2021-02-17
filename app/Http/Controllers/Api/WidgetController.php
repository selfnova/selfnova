<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WidgetSetting;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data['widgets'] = Widget::getSettings()->get();

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
		$upd = [];

		if ( $request->get('settings') )
			$upd['settings'] = $request->get('settings');

		if ( $request->get('active') !== null )
			$upd['active'] = $request->get('active');

		WidgetSetting::where( 'w_id', $request->get('id') )
			->where('u_id', Auth::id() )
			->update($upd);

        return response()->json( ['success' => true] ) ?: '{}';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Widget  $widget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $widget)
    {
        if ( $request->get('settings') )
			$upd['settings'] = $request->get('settings');

		if ( $request->get('active') !== null )
			$upd['active'] = $request->get('active');

		$ws = WidgetSetting::where( 'w_id', $widget )
			->where('u_id', Auth::id() )
			->update($upd);

		return response()->json( ['success' => true, 'data' => $ws] ) ?: '{}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Widget  $widget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Widget $widget)
    {
        //
    }
}
