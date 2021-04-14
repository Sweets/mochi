<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use App\Models\User;

use Carbon\Carbon;

class UserController extends Controller
{
    public static function create(Request $request)
	{
		$user = new User;
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->registered = $user->last_active = Carbon::now();
		$user->api_token = Str::uuid()->toString();
		$user->data = $user->statistics = $user->settings = [];

		$user->save();

		Cache::put("user_{$user->id}", (string)$user);

		return $user;
	}

	public static function get($id = -1)
	{
		if ($id)
		{
			return Cache::get("user_{$id}", function() {
				return User::find($id);
			});
		}
	}
}
