<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;

use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
	public static function authenticate(Request $request)
	{
//		$request->name_or_email;
		$credentials = [
			'password' => $request->password
		];

		if (User::where('email', $request->name_or_email)->count())
			$credentials['email'] = $request->name_or_email;
		else if (User::where('name', $request->name_or_email)->count())
			$credentials['name'] = $request->name_or_email;

		if (Auth::attempt($credentials)) {
			return ['token' => Auth::user()->api_token];
		}
	}
}
