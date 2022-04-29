<?php

namespace App\Http\Controllers;

use App\Events\MessageAdd;
use App\Http\Controllers\Controller;
use App\Models\Message;
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
		$message = Message::create([
			'u_id' => Auth::id(),
			'chat_id' => $request->get('chat_id'),
			'text' => $request->get('text'),
		]);

		$response = [
			'success' => true,
			'day' => $message->created_at->format('j m'),
			'message' => $message
		];

		broadcast(new MessageAdd( $message ) )->toOthers();

		return response()->json( $response ) ?: '{}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
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
