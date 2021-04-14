<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

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
		$post = null;
		$from_cache = Cache::get("post_{$id}");

		if ($from_cache)
			$post = json_decode($from_cache);
		else
			$post = Post::find($id);

		$post->owning_user = User::find($post->owning_user);

		return $post;
	}
}
