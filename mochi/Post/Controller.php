<?php

namespace Mochi\Post;

use App\Http\Controllers\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Carbon\Carbon;

use Mochi\Category\Model as Category;
use Mochi\Thread\Model	 as Thread;
use Mochi\Post\Model	 as Post;

use Mochi\User\Controller as UserController;

class Controller extends BaseController
{
    public static function create(Request $request)
	{
		$post = new Post;
		$thread = new Thread; // if there is no `thread` in the request headers

		$category = Category::find($request->category); // optimize this
		$cat_statistics = $category->statistics;

		if (!$request->thread)
		{
			$thread->title = $request->title;
			$thread->category = $category->id;
			$thread->user = Auth::id();
			$thread->created_at = Carbon::now();

			$thread->save();
			$cat_statistics->threads++;

			Cache::put("thread_{$thread->id}", (string)$thread);
		}
		else
			$thread = Thread::find($request->thread); // optimize this

		$post->content = $request->content;
		$post->thread = $thread->id;
		$post->user = Auth::id();
		$post->data = [];

		$post->save();
		$category->last_post = $post->id;
		$cat_statistics->posts++;

		$category->statistics = $cat_statistics;
		$category->save();

		Cache::put("category_{$category->id}", (string)$category);
		Cache::put("post_{$post->id}", (string)$post);

		return $post;
	}

	public static function get($id)
	{
		$post = Cache::get("post_{$id}", function() use ($id) {
			return Post::find($id);
		});

		if (!$post)
			return null;

		if (gettype($post) == 'string')
			$post = (new Post)->forceFill(json_decode($post, $array = true));

		$post->created = $post->created_at->diffForHumans();
		$post->updated = $post->updated_at->diffForHumans();

		return $post;
	}

	public static function get_extended($id, $thread = false, $user = false)
	{
		$post = Controller::get($id);

		if ($thread)
			$post->thread = Thread::find($post->thread); // optimize this

		if ($user)
			$post->user = UserController::get($post->user);

		return $post;
	}
}
