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

	protected static function booted()
    {
        static::created(function ($comment) {
			if ( $comment->type == 'review' ) {
				Review::find($comment->type_id)->increment('count_comm');
			} else {
				Post::find($comment->type_id)->increment('count_comm');
			}
        });

		static::deleted(function ($comment) {
			if ( $comment->type == 'review' ) {
				Review::find($comment->type_id)->decrement('count_comm');
			} else {
				Post::find($comment->type_id)->decrement('count_comm');
			}
        });
    }

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}
}
