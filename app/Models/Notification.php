<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
		'status'
	];

	protected $casts = [
		'created_at' => 'datetime:d.m.Y в H:i',
	];

	public function comment()
	{
		return $this->hasOne( 'App\Models\Comments', 'id', 'type_id' )
			->with('user:id,name,last_name,avatar');
	}

	protected function serializeDate($date)
	{
		return $date->timezone('Europe/Moscow')->format('Y-m-d H:i:s');
	}

	protected function scopeForUser($query, $user_id, $onlyOnread = false)
	{
		$query->where('u_id', $user_id)
			  ->latest()
			  ->limit(15);

		if ($onlyOnread)
			$query->where('status', 0);
	}
}
