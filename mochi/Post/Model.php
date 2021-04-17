<?php

namespace Mochi\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use HasFactory;

	public $table = "posts";

	protected $hidden = [
	];

	protected $casts = [
		'data' => 'array',
	];
}
