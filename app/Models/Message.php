<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'created_at' => 'datetime'
	];

	protected $appends = ['time'];

	public function getPhotoAttribute( $value )
	{
		if ( !$value ) return null;

		return env('APP_URL').'/storage/'.($value);
	}

	public function getTimeAttribute()
	{
		return $this->created_at->format('H:i');
	}
}
