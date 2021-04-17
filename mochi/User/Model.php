<?php

namespace Mochi\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Model extends Authenticatable
{
	use HasFactory;

	public $table = "users";
	public $timestamps = false;

	protected $hidden = [
		'password',
		'email',
		'api_token',
		'remember_token',
	];

	protected $casts = [
		'data'		 => 'object',
		'statistics' => 'object',
		'settings'	 => 'object',
		'last_active_at'=> 'datetime:Carbon',
		'registered_at' => 'datetime:Carbon'
	];
}
