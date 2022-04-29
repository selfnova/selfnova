<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Product extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'photos' => 'json',
		'updated_at' => 'datetime:d.m.Y в H:i',
	];

	public function getPhotosAttribute( $value )
	{
		if ( !$value ) return $value;

		return [env('APP_URL').'/storage/'.json_decode($value)[0]];
	}

	protected static function booted()
    {

    }

	public function group()
	{
		return $this->hasOne( 'App\Models\Group', 'id', 'g_id' );
	}

	public function comments()
	{
		return $this->hasMany( 'App\Models\Comments', 'type_id', 'id' )
			->with('user:id,name,last_name,avatar');
	}

}
