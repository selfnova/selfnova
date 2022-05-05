<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatUser extends Model
{
	use HasFactory;

	protected $fillable = ['u_id', 'g_id', 'chat_id'];

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}

	public function group()
	{
		return $this->hasOne( 'App\Models\Group', 'id', 'g_id' );
	}
}
