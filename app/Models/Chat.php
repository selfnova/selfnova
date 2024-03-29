<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Illuminate\Events\queueable;

class Chat extends Model
{
	use HasFactory;

	protected $casts = [
		'to_from' => 'json',
	];

	protected $fillable = ['creator', 'name', 'type', 'to_from'];

	/**
	 * createChat
	 *
	 * @param  mixed $request
	 * @return Object
	 */

	public function group()
	{
		return $this->hasOne( 'App\Models\Group', 'id', 'g_id' );
	}

	protected static function booted()
    {
		static::deleted( queueable(function ($chat)
		{
			ChatUser::where('chat_id', $chat->id)->delete();
			Message::where('chat_id', $chat->id)->delete();
		}));
    }
	public static function createChat( $request )
	{
		$chat_id = self::check( $request );

		if ( !$chat_id )
		DB::transaction(function () use ($request, &$chat_id)
		{
			$u_id = $request->get('u_id');

			if ( $request->get('g_id') ) {
				$group = Group::select('u_id')->find($request->get('g_id'));
				$u_id = $group->u_id;
			}

			$data = [
				'creator' => Auth::id(),
				'type' => $request->get('u_id') ? 1 : 2,
				'name' => $request->get('name'),
				'to_from' => [
					Auth::id() => $u_id,
					$u_id => Auth::id()
				]
			];

			$chat = Chat::create($data);
			$chat_id = $chat->id;

			ChatUser::create([
				'u_id' => Auth::id(),
				'g_id' => $request->get('g_id'),
				'chat_id' => $chat->id
			]);

			ChatUser::create([
				'u_id' => $u_id,
				'g_id' => $request->get('g_id'),
				'chat_id' => $chat->id
			]);
		});

		return $chat_id;
	}

	public static function check( $request )
	{
		$chat = ChatUser::select('chat_users.chat_id as id')
			->join('chat_users as cu2', 'cu2.chat_id', '=', 'chat_users.chat_id')
			->where('chat_users.u_id', Auth::id())
			->where('cu2.u_id', $request->get('u_id'))
			->value('id');

		return $chat;
	}

	public function user()
	{
		return $this->hasOne( 'App\Models\User', 'id', 'u_id' );
	}

}
