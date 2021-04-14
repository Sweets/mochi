<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Category;

Route::get('/index', function(Request $request) {
	return response()->json([
		CategoryController::get(1),
		CategoryController::get(2),
	]);
});

Route::prefix('user')->group(function() {
	Route::post('/register', function(Request $request) {
		return response()->json([
			'token' => UserController::create($request)->api_token
		]);
	});

	Route::post('/login', function(Request $request) {
		return response()->json(AuthController::authenticate($request));
	});

	Route::get('/{id}', function(Request $request, $id) {
		// Refresh cache
//		Cache::put("user_{$id}", (string)User::find($id));
		return response()->json(json_decode(Cache::get("user_{$id}")));
	});
});

Route::middleware('auth:api')->group(function() {
	/* Categories */
	Route::post('category', function(Request $request) {
		return response()->json(CategoryController::create($request));
	});

	/* Threads */
	Route::prefix('thread')->group(function() {
		Route::get('/{id}', function(Request $request, $id) {
			// Threads are notional in mochi.
			return response()->json([
				PostController::get(1),
				PostController::get(2),
			]);
		});
	});

	/* Posts */
	Route::post('post', function(Request $request) {
		return response()->json(PostController::create($request));
	});

	Route::prefix('post')->group(function() {
		// /post/1 - get by id
		// /post/1 - PATCH, for updating posts
		// /post/1 - DELETE, for deleting or soft deleting
	});
});
