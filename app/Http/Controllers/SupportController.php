<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function store(Request $request)
    {
        $request->input('name')
    }

}
