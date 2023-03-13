<?php

namespace App\Models;

use Illuminate\Support\Str;
use Snap\Admin\Modules\ModuleModel as Model;

class Invite extends Model {

	protected $fillable = [
		'email',
		'token',
		'role'
	];

    public static $rules = [
        'email' => 'email|required',
        'role' => 'required',
    ];

	public static function boot()
	{
		parent::boot();

		static::creating(function($invite)
		{
			$invite->token = Str::random();
		});
	}
}


