<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
	public static function createChat( $request )
	{
		$chat_id = self::check( $request );

		if ( !$chat_id )
		DB::transaction(function () use ($request, &$chat_id)
		{
			$data = [
				'creator' => Auth::id(),
				'type' => $request->get('u_id') ? 1 : 2,
				'name' => $request->get('name'),
				'to_from' => [
					Auth::id() => $request->get('u_id'),
					$request->get('u_id') => Auth::id()
				]
			];

			$chat = Chat::create($data);
			$chat_id = $chat->id;

			ChatUser::create([
				'u_id' => Auth::id(),
				'chat_id' => $chat->id
			]);

			ChatUser::create([
				'u_id' => $request->get('u_id'),
				'chat_id' => $chat->id
			]);
		});

		return $chat_id;
	}

	public static function check( $request )
	{
		$chat = ChatUser::select('chat_users.id')
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
