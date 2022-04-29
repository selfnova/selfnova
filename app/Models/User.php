<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use function Illuminate\Events\queueable;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'name',
		'last_name',
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
		'private_set' => 'json'
	];

	protected $appends = ['full_name'];

	public function getFullNameAttribute()
	{
		return "{$this->name} {$this->last_name}";
	}

	public function getAvatarAttribute( $value )
	{
		return env('APP_URL').'/storage/'.($value);
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


}
