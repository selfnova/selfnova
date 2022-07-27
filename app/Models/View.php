<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'post_id',
		'group_id',
		'user_id',
		'viewer_id',
	];

	public static function boot()
	{
		parent::boot();

		static::creating(function ($model) {

			if (!$model->check())
				return false;

			$model->created_at = $model->freshTimestamp();
		});

		static::created(function ($model) {
			if ($model->post_id)
				Post::find($model->post_id)->increment('views');
			elseif ($model->group_id)
				Group::find($model->group_id)->increment('views');
			elseif ($model->user_id)
				User::find($model->user_id)->increment('views');
		});
	}

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'viewer_id' );
	}

	public function check()
	{
		$exists = self::where( $this->toArray() )
				->exists();
		return !$exists;
	}
}
