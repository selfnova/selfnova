<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Post extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'photos' => 'json',
		'options' => 'json',
		'updated_at' => 'datetime:d.m.Y в H:i',
	];

	public function getPhotosAttribute( $value )
	{
		if ( !$value ) return $value;

		return [env('APP_URL').'/storage/'.json_decode($value)[0]];
	}

	protected static function booted()
    {
        static::deleted(function ($post) {
            Comments::where('type_id', $post->id)->delete();

			if ( $post->repost_id ) {
				Post::find($post->repost_id)->decrement('count_repost');
			}
        });

		static::created(function ($post) {
			if ( $post->repost_id ) {
				Post::find($post->repost_id)->increment('count_repost');
			}
        });
    }

	public static function followingPosts()
	{
		$followings = self::where('type', 'post')
			->with('repost', 'user:id,name,last_name,avatar')
			->whereIn('u_id', function( $query )
			{
				$query->select('to_id')
				->from( with( new UserFollow )->getTable() )
				->where('u_id', Auth::user()->id );
			})
			->latest()
			->limit(4)
			->get();

		return $followings;
	}

	public static function followingGroupPosts()
	{
		$followings = self::where('type', 'post')
			->with('group:id,name,avatar')
			->whereIn('g_id', function( $query )
			{
				$query->select('to_id')
				->from( with( new UserFollow )->getTable() )
				->where('u_id', Auth::user()->id );
			})
			->latest()
			->limit(4)
			->get();

		return $followings;
	}

	public function repost()
	{
		return $this->hasOne( 'App\Models\Post', 'id', 'repost_id' )
			->select('posts.*')
			->selectRaw('1 as reposted')
			->with('group:id,name,avatar', 'user:id,name,last_name,avatar', 'comments');
	}

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
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
