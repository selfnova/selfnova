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

	public function getTimeAttribute()
	{
		return $this->created_at->format('H:i');
	}
}
