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
		$category = Cache::get("category_{$id}", function() use ($id) {
			return Category::find($id);
		});

		if (!$category)
			return null;

		if (gettype($category) == 'string')
			$category = (new Category)->forceFill(json_decode($category, $array = true));

		if ($category->last_post)
			$category->last_post = PostController::get($category->last_post);

		return $category;
	}
}
