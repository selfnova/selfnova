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

	protected function serializeDate($date)
	{
		return $date->timezone('Europe/Moscow')->format('Y-m-d H:i:s');
	}

	protected $appends = ['time'];

	public function getPhotoAttribute( $value )
	{
		if ( !$value ) return null;

		return config('app.url').'/storage/'.($value);
	}

	public function getTimeAttribute()
	{
		return $this->created_at->format('H:i');
	}
}
