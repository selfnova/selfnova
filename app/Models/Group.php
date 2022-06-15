<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes, Attachable;

	protected $fillable = [
		'u_id',
		'name',
		'avatar',
		'about',
		'service',
	];

	protected $hidden = [
        'adm_comment',

    ];

	protected $allowedSorts = [
        'created_at'
    ];

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

	public function scopeIsFollow($query)
    {
        return $query->select('groups.*', 'group_follows.to_id as isFollow')
			->leftJoin('group_follows', function ($join) {
				$join->on('groups.id', '=', 'group_follows.to_id')
					 ->where('group_follows.u_id', Auth::id() );
			});
    }

	// public function scopeMyTrashed($query)
    // {
    //     return $query->select('users.*', 'user_follows.to_id as isFollow')
	// 		->leftJoin('user_follows', function ($join) {
	// 			$join->on('users.id', '=', 'user_follows.to_id')
	// 				 ->where('user_follows.u_id', Auth::id() );
	// 		});
    // }
}
