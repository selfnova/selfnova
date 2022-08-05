<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Post;
use App\Models\Comments;
use App\Models\Notification;

class CommentAdd implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
	protected $comment;

	public function __construct( Comments $comment, $u_id )
	{
		$this->comment = $comment;
		$post = Post::find($comment->type_id);

		$noty = new Notification;

		$noty->u_id = $u_id;
		$noty->type_id = $comment->id;
		$noty->type = 'comment';
		$noty->status = 0;
		$noty->save();
	}

	public function broadcastWith()
	{
		return Comments::with('user:id,name,avatar')
			->find( $this->comment->id )
			->toArray();
	}
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('comment');
    }
}
