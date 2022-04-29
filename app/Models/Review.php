<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
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
        static::deleted(function ($review) {
			$ratings = Review::select('rating')
				->where('g_id', $review->g_id)
				->get();

			$avg_rating = $ratings->avg('rating');

			Group::where('id', $review->g_id)
				->update(['rating' => $avg_rating]);
        });
		static::created(function ($review) {
			$ratings = Review::select('rating')
				->where('g_id', $review->g_id)
				->get();

			$avg_rating = $ratings->avg('rating');

			Group::where('id', $review->g_id)
				->update(['rating' => $avg_rating]);
        });
    }

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}

	public function comments()
	{
		return $this->hasMany( 'App\Models\Comments', 'type_id', 'id' )
			->where('type', 'review')
			->with('user:id,name,last_name,avatar');
	}
}
