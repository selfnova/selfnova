<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'updated_at' => 'datetime:d.m.Y в H:i',
		'created_at' => 'datetime:d.m.Y в H:i',
	];

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}
}
