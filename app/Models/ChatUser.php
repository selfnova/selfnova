<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatUser extends Model
{
	use HasFactory;

	protected $fillable = ['u_id', 'chat_id'];

	public function message()
	{
		return $this->hasOne( 'App\Models\Message', 'id', 'last_msg' );
	}

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}
}
