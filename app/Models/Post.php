<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Post extends Model
{
	use HasFactory;

	protected $casts = [
		'date' => 'datetime:d.m.Y в H:i',
	];

	public static function followingPosts()
	{
		$followings = self::where('type', 'post')
			->with('repost.group:id,name,avatar', 'user:id,name,avatar')
			->whereIn('u_id', function( $query )
			{
				$query->select('to_id')
				->from( with( new UserFollow )->getTable() )
				->where('u_id', Auth::user()->id );
			})
			->orderBy('date', 'desc')
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
				->from( with( new GroupFollow )->getTable() )
				->where('u_id', Auth::user()->id );
			})
			->orderBy('date', 'desc')
			->limit(4)
			->get();

		return $followings;
	}

	public function repost()
	{
		return $this->hasOne( 'App\Models\Post', 'id', 'repost_id' );
	}

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}

	public function group()
	{
		return $this->hasOne( 'App\Models\Group', 'id', 'g_id' );
	}

}
