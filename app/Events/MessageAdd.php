<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageAdd implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

	public function __construct( Message $message)
	{
		$this->message = $message;
	}

	public function broadcastWith()
    {
        return [
			'day' => $this->message->created_at->format('j m'),
			'message' => $this->message
		];
	}

    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->message->chat_id);
	}

	public function broadcastAs()
    {
        return 'message.add';
    }
}
