<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Post;
use App\Http\Controllers\UserController;

class PostController extends Controller
{
    public static function create(Request $request)
	{
		$post = new Post;
		$post->title = $request->title;
		$post->content = $request->content;
		$post->category = $request->category;
		$post->data = [];

		if ($request->thread)
			$post->thread = $request->thread;

		$post->owning_user = Auth::id();

		$category = Category::find($post->category);
		if (!$category)
			return []; // Category not found, error out.

		$post->save();

		$category->last_post = $post->id;
		$statistics = $category->statistics;
		$statistics->posts++;
		$statistics->threads++;
		$category->statistics = $statistics;

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

		$post->owning_user = UserController::get($post->owning_user);

		return $post;
	}
}
