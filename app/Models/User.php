<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use function Illuminate\Events\queueable;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'name',
		'email',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'born' => 'datetime:d.m.Y',
		'email_verified_at' => 'datetime',
		'private_set' => 'json',
	];

	protected $appends = ['full_name'];

	public function getFullNameAttribute()
	{
		return "{$this->name} {$this->last_name}";
	}

	protected static function booted()
    {
		static::created( queueable(function ($user)
		{
			$ws = [];

			foreach ( Widget::select('id')->get() as $item)
			{
				$ws[] = [
					'u_id' => $user->id,
					'w_id' => $item['id'],
					'active' => 1
				];
			}

			WidgetSetting::insert($ws);
		}));
    }

	public function scopeIsFollow($query)
    {
        return $query->select('users.*', 'user_follows.to_id as isFollow')
			->leftJoin('user_follows', function ($join) {
				$join->on('users.id', '=', 'user_follows.to_id')
					 ->where('user_follows.u_id', Auth::id() );
			});
    }

	/**
	 * posts
	 *
	 * @return Collection
	 */
	public function posts()
    {
		return $this->hasMany(Post::class, 'u_id', 'id')
			->leftJoin('posts as reposted', function ($join) {
				$join->on('posts.id', '=', 'reposted.repost_id')
					->where('reposted.u_id', Auth::user()->id);
			})
			->select('posts.*', 'reposted.id as reposted')
			->with('repost', 'user:id,name,avatar', 'comments')
			->latest();
	}

}
