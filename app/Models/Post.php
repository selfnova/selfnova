<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Post extends Model
{
	use HasFactory, AsSource, Filterable, Attachable;

	protected $guarded = [];

	protected $casts = [
		'photos' => 'json',
		'options' => 'json',
		'updated_at' => 'datetime:d.m.Y в H:i',
		'created_at' => 'datetime:d.m.Y в H:i',
	];

	protected $allowedSorts = [
        'created_at'
    ];

	protected function serializeDate($date)
	{
		return $date->timezone('Europe/Moscow')->format('Y-m-d H:i:s');
	}


	public function getPhotosAttribute( $value )
	{
		if ( !$value ) return $value;

		return [config('app.url').'/storage/'.json_decode($value)[0]];
	}

	protected static function booted()
    {
        static::deleted(function ($post) {
            Comments::where('type_id', $post->id)->where('type', 'post')->delete();

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

	public static function followingPosts( $count = null )
	{
		$followings = self::where(function ($query) {
				$query->where('type', 'post')
					->orWhere('type', 'photo');
			})
			->with('repost', 'user:id,name,last_name,avatar', 'viewers')
			->whereIn('u_id', function( $query )
			{
				$query->select('to_id')
				->from( with( new UserFollow )->getTable() )
				->where('u_id', Auth::user()->id );
			})
			->whereNull('g_id')
			->latest();

		if ( $count ) $followings = $followings->simplePaginate($count);
		else $followings = $followings->limit(3)->get();

		return $followings;
	}

	public static function followingGroupPosts( $count = null )
	{
		$followings = self::where('type', 'post')
			->with('group:id,name,avatar', 'viewers')
			->whereIn('g_id', function( $query )
			{
				$query->select('to_id')
				->from( with( new GroupFollow )->getTable() )
				->where('u_id', Auth::user()->id );
			})
			->latest();

		if ( $count ) $followings = $followings->simplePaginate($count);
		else $followings = $followings->limit(3)->get();

		return $followings;
	}

	public function repost()
	{
		return $this->hasOne( 'App\Models\Post', 'id', 'repost_id' )
			->select('posts.*')
			->selectRaw('1 as reposted')
			->with('group:id,name,avatar', 'user:id,name,last_name,avatar', 'comments', 'viewers');
	}

	public function isView()
	{
		return $this->hasOne( 'App\Models\View', 'post_id', 'id' )
			->where('viewer_id', Auth::id() );
	}

	public function viewers()
	{
		return $this->hasOne( 'App\Models\View', 'post_id', 'id' )
			->orderBy('id', 'DESC')
			->with('user:id,name,last_name,avatar');
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
			->with('user:id,name,last_name,avatar')
			->limit(3);
	}

}
