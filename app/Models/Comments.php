<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Comments extends Model
{
	use HasFactory, AsSource, Filterable, Attachable;

	protected $guarded = [];

	protected $casts = [
		'updated_at' => 'datetime:d.m.Y в H:i',
		'created_at' => 'datetime:d.m.Y в H:i',
	];

	protected $with = [
		'user'
	];

	protected $allowedSorts = [
        'created_at'
    ];

	protected static function booted()
    {
        static::created(function ($comment) {
			if ( $comment->type == 'review' ) {
				Review::find($comment->type_id)->increment('count_comm');
			} else if ( $comment->type == 'news' ) {
			} else {
				Post::find($comment->type_id)->increment('count_comm');
			}
        });

		static::deleted(function ($comment) {
			if ( $comment->type == 'review' ) {
				Review::find($comment->type_id)->decrement('count_comm');
			} else if ( $comment->type == 'news' ) {
			} else {
				Post::find($comment->type_id)->decrement('count_comm');
				Notification::where('type', 'comment')->where('type_id', $comment->id)->delete();
			}
        });
    }

	protected function serializeDate($date)
	{
		return $date->timezone('Europe/Moscow')->format('Y-m-d H:i:s');
	}

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}

	public function replys()
	{
		return $this->hasMany( 'App\Models\Comments', 'reply_id', 'id' );
	}
}
