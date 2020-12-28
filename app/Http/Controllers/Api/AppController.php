<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends \App\Http\Controllers\Controller
{

	public function layout()
	{
		$data['user'] = Auth::user();

		return response()->json( $data ) ?: '{}';
	}

}
