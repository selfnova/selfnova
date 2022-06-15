<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Product extends Model
{
	use HasFactory, AsSource, Filterable, Attachable;

	protected $guarded = [];

	protected $casts = [
		'photos' => 'json',
		'updated_at' => 'datetime:d.m.Y в H:i',
	];

	protected $allowedSorts = [
        'created_at'
    ];

	public function getPhotosAttribute( $value )
	{
		if ( !$value ) return $value;

		return [config('app.url').'/storage/'.json_decode($value)[0]];
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
