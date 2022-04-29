<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

		if ( !Auth::user()->hasVerifiedEmail() ) {
			return response()->json([
                'success' => false,
                'error' => [
					'msg' => 'Подтвердите e-mail',
					'code' => '401',
				]
            ], 200);
		}

        $token = Auth::user()->createToken(config('app.name'));
        $token->token->expires_at = Carbon::now()->addYear();

        $token->token->save();

        return response()->json([
			'success' => true,
            'token_type' => 'Bearer',
            'token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);
    }
}
