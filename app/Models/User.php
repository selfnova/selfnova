<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use function Illuminate\Events\queueable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

use Orchid\Platform\Models\User as Authenticatable;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;


class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, SoftDeletes, HasFactory, Notifiable, AsSource, Filterable, Attachable;

    protected $fillable = [
        'name',
		'last_name',
		'city',
        'email',
        'password',
        'permissions',
		"photoblog"
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
        'adm_comment',
		'email_verified_at',
		'created_at',
		'update_at',
    ];

    protected $casts = [
		'born' => 'datetime:d.m.Y',
		'private_set' => 'json',
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

	protected $appends = ['full_name'];

    protected $allowedFilters = [
        'id',
        'name',
        'last_name',
        'email',
        'permissions',
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'last_name',
        'email',
        'updated_at',
        'created_at',
    ];

	public function getFullNameAttribute()
	{
		return "{$this->name} {$this->last_name}";
	}

	public function getAvatarAttribute( $value )
	{
		if ( !$value ) return null;

		return config('app.url').'/storage/'.($value);
	}

	public function setAvatarAttribute( $value )
	{
		if ( !$value ) return null;

		$this->attributes['avatar'] = str_replace( config('app.url').'/storage/', '', $value);
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

			$follow = new GroupFollow;

			$follow->u_id  = $user->id;
			$follow->to_id = 1;

			$follow->save();

			User::where('id', $user->id)->increment('following_groups');
			Group::where('id', 1)->increment('followers');

			$followUser = new UserFollow;

			$followUser->to_id = 1;
			$followUser->u_id = $user->id;

			$followUser->save();

			User::where('id', $user->id)->increment('followings');
			User::where('id', 1)->increment('followers');
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

	public function notifications()
	{
		return $this->morphMany(SystemNotifications::class, 'notifiable')->orderBy('created_at', 'desc');
	}
}
