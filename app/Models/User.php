<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use HasFactory;

	public $timestamps = false;

	protected $hidden = ['password', 'email', 'api_token', 'remember_token'];

	protected $casts = [
		'data'		 => 'object',
		'statistics' => 'object',
		'settings'	 => 'object'
	];
}
