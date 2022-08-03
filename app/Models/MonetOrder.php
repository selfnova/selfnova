<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonetOrder extends Model
{
	protected $fillable = [
		'group_id',
		'user_id',
		'sender_id',
		'is_approved',
	];

	public static function boot()
	{
		parent::boot();

		static::creating(function ($model) {

		});
	}
}
