<?php

namespace Mochi\Thread;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use HasFactory;

	public $table = "threads";
	public $timestamps = false;

	protected $casts = [
		'created_at' => 'datetime:Carbon'
	];
}
