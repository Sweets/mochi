<?php

namespace Mochi\User;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Mochi\User\Model;

class Controller extends BaseController
{
	/* Authentication related*/

	public static function authenticate(Request $request)
	{
		$n_or_e = $request->name_or_email;
		$credentials = [
			'password' => $request->password
		];

		if (Model::where('email', $n_or_e)->count())
			$credentials['email'] = $n_or_e;
		else if (Model::where('name', $n_or_e)->count())
			$credentials['name']  = $n_or_e;

		if (Auth::attempt($credentials))
			return ['token' => Auth::user()->api_token];

		return [];
	}

	public static function authenticated()
	{
		return Controller::get(Auth::id());
	}

	/* API */
    public static function create(Request $request)
	{
		$user = new Model;
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->registered_at = $user->last_active_at = Carbon::now();
		$user->api_token = Str::uuid()->toString();
		$user->data = $user->statistics = $user->settings = [];

		$user->save();

		Cache::put("user_{$user->id}", (string)$user);

		return $user;
	}

	public static function get($id = -1)
	{
		$user = Cache::get("user_{$id}", function() use ($id) {
			return Model::find($id);
		});

		if (!$user) // No user found
			return null;

		if (gettype($user) == 'string') // forceFill JSON
			$user = (new Model)->forceFill(json_decode($user, $array = true));

		$user->registered = $user->registered_at->diffForHumans();
		$user->last_active = $user->last_active_at->diffForHumans();

		if (Carbon::now()->subMinutes(15)->gt($user->last_active_at))
			$user->online = false;
		else
			$user->online = true;

		return $user;
	}
}
