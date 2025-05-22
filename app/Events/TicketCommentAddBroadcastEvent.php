<?php

namespace App\Events;

use App\Models\TicketComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;



class TicketCommentAddBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public TicketComment $comment;
    public string $action;

    public function __construct(TicketComment $comment,string $action)
    {
        $this->dontBroadcastToCurrentUser();
        $this->action = $action;
        $this->comment = $comment;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('Ticket.' . $this->comment->ticket_id);
    }



    public function broadcastWith(): array
    {
        return [
            'comment' => [
                'id' => $this->comment->id,
                'user_id' => $this->comment->user_id,
                'user_name' => $this->comment->user->name,
                'text' => $this->comment->text,
                'created_at' => $this->comment->created_at,
                'action' => $this->action,

            ],
        ];
    }
}
