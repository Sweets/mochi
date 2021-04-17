<?php

namespace Mochi\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use HasFactory;

	public $table = "category";
	public $timestamps = false;

	protected $casts = [
		'statistics' => 'object'
	];
}
