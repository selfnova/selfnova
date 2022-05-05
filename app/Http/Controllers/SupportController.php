<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\SupportMail;

class SupportController extends \App\Http\Controllers\Controller
{
    public function store(Request $request)
    {
		Mail::to('yeliseymaslov@gmail.com')->send(new SupportMail($request->all()));

		return response()->json( ['success' => true] ) ?: '{}';
	}

}
