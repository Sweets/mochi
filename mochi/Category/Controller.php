<?php

namespace Mochi\Category;

use App\Http\Controllers\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Mochi\Category\Model;
use Mochi\Post\Model as Post;
use Mochi\Post\Controller as PostController;

class Controller extends BaseController
{
    public static function create(Request $request)
	{
		$category = new Model;

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
			return Model::find($id);
		});

		if (!$category)
			return null;

		if (gettype($category) == 'string')
			$category = (new Model)->forceFill(json_decode($category, $array = true));

		return $category;
	}

	public static function get_extended($id, $extend_post = false)
	{
		$category = Controller::get($id);

		if ($category->last_post)
			$category->last_post = PostController::get_extended($category->last_post,
				$thread = $extend_post, $user = $extend_post);

		return $category;
	}
}
