<?php

namespace Mochi\Thread;

use App\Http\Controllers\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Mochi\Thread\Model;

class Controller extends BaseController
{
    public static function create(Request $request)
	{
	}

	public static function get($id = -1)
	{
		$thread = Cache::get("thread_{$id}", function() use ($id) {
			return Model::find($id);
		});

		if (!$thread)
			return null;

		if (gettype($thread) == 'string')
			$thread = (new Model)->forceFill(json_decode($thread, $array = true));

		$thread->created = $thread->created_at->diffForHumans();

		return $thread;
	}
}
