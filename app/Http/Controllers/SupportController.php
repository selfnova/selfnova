<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\SupportMail;
use App\Models\Report;

class SupportController extends \App\Http\Controllers\Controller
{
    public function store(Request $request)
    {
		Mail::to('yeliseymaslov@gmail.com')->send(new SupportMail($request->all()));

		return response()->json( ['success' => true] ) ?: '{}';
	}

	public function report(Request $request)
    {
		$report = new Report();

		$report->u_id = $request->get('u_id');
		$report->type_id = $request->get('id');
		$report->type = $request->get('type');

		$report->save();

		return response()->json( ['success' => true] ) ?: '{}';
	}

}
