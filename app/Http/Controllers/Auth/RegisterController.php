<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'confirmed', 'min:6', 'max:64'],
		]);

		$user = User::create([
			'name' => $request->name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		event(new Registered($user));

		return response()->json([
            'url' => $request->last_name,
            'success' => true,
        ], 200);
    }
}
