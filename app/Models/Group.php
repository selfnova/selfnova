<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;

	protected $fillable = [
		'u_id',
		'name',
		'avatar',
		'about',
		'service',
	];

	public function getAvatarAttribute( $value )
	{
		return env('APP_URL').'/storage/'.($value);
	}

	public function scopeIsFollow($query)
    {
        return $query->select('groups.*', 'group_follows.to_id as isFollow')
			->leftJoin('group_follows', function ($join) {
				$join->on('groups.id', '=', 'group_follows.to_id')
					 ->where('group_follows.u_id', Auth::id() );
			});
    }
}
