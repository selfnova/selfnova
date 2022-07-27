<?php

namespace App\Http\Controllers;

use App\Events\MessageAdd;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Chat;
use App\Models\ChatUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$data = [
			'u_id' => Auth::id(),
			'chat_id' => $request->get('chat_id'),
			'text' => $this->replaceLink( $request->get('text') ),
		];

		if ( $request->get('video') ) $data['video'] = $request->get('video');
		if ( $request->file('image') )
			$data['photo'] =
				$request->file('image')->store(
					'messages', 'public'
				);

		$message = Message::create($data);

		Chat::where('id', $request->get('chat_id'))
			->update(['last_msg' => $message->id]);

		ChatUser::where('chat_id', $request->get('chat_id'))
			->where('u_id', '!=', $request->user()->id)
			->first()
			->increment('unread_msg');

		$response = [
			'success' => true,
			'day' => $message->created_at->format('j m'),
			'message' => $message
		];

		broadcast(new MessageAdd( $message ) )->toOthers();

		return response()->json( $response ) ?: '{}';
    }

	protected function replaceLink( $text )
	{
		$preg = '/((https|http):\/\/[a-zA-Z0-9-.\/\?\=\&\%\_\(\)\,а-яёА-Я\#]+)/';
		$link = '<a target="_blank" href="$1">$1</a>';

		return preg_replace($preg, $link, $text);
	}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
	public function read(Request $request, $chat_id)
    {
		Message::whereIn('id', $request->get('ids'))->update(['is_read' => true]);
		ChatUser::where('chat_id', $chat_id)
			->where('u_id', $request->user()->id)
			->update(['unread_msg' => 0]);
		return response()->json( $request->get('ids') ) ?: '{}';
    }
    public function show( $chat_id )
    {
		$messages = Message::where('chat_id', $chat_id)
			->latest()
			->get()
			->reverse()
			->groupBy( function ($item, $key) {
				return $item->created_at->format('j m');
			});

		return response()->json( $messages ) ?: '{}';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
