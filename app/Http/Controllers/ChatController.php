<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function index()
    {
		$select = [
			'chats.id', 'chats.name',
			'chats.to_from->'.Auth::id().' as u_id',
		];

		$data = ChatUser::select( $select )
			->where('u_id', Auth::id())
			->join('chats', 'chats.id', '=', 'chat_users.chat_id')
			->with('message')
			->with('user:id,name,last_name,avatar')
			->orderBy('chats.last_msg', 'DESC')
			->get();

		return response()->json( $data ) ?: '{}';
    }

    public function store(Request $request)
    {
		$chat_id = Chat::createChat($request);

		$response = ['chat_id' => $chat_id];

		return response()->json( $response ) ?: '{}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $select = [
			'chats.*', 'cu.u_id'
		];

		$data = Chat::select( $select )
			->join('chat_users as cu', function ($join) {
				$join->on('cu.chat_id', '=', 'chats.id')
					->where('u_id', '!=', Auth::id() );
			})
			->with('user:id,name,last_name,avatar')
			->find( $id );

		return response()->json( $data ) ?: '{}';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
