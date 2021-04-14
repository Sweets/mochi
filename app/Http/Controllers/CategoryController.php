<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\PostController;

use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    public static function create(Request $request)
	{
		$category = new Category;

		$category->name = $request->name;
		$category->description = $request->description;
		$category->statistics = [
			'posts'   => 0,
			'threads' => 0
		];

		$category->save();
		Cache::put("category_{$category->id}", (string)$category);

		return $category;
	}

	public static function get($id)
	{
		$category = null;

		$from_cache = Cache::get("category_{$id}");
		if ($from_cache)
			$category = json_decode($from_cache);
		else
			$category = Category::find($id);

		if ($category->last_post)
			$category->last_post = PostController::get($category->last_post);

		return $category;
	}
}
