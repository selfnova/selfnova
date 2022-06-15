<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyEmailController extends Controller
{
	/**
	 * Mark the authenticated user's email address as verified.
	 *
	 * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function __invoke(Request $request)
	{
		$user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.front_url'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(config('app.front_url').'/email-confirmed');
	}
}
