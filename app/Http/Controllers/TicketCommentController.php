<?php

namespace App\Http\Controllers;

use App\Events\TicketCommentAddBroadcastEvent;
use App\Http\Requests\TicketCommentCreateRequest;
use App\Http\Resources\TicketCommentResource;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    /**
     * @param TicketCommentCreateRequest $request
     * @param Ticket $ticket
     * @return TicketCommentResource
     */
    public function store(TicketCommentCreateRequest $request, Ticket $ticket): TicketCommentResource
    {
        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'text' => $request->text,
        ]);


        broadcast(new  TicketCommentAddBroadcastEvent($comment, 'create'));
        return new TicketCommentResource($comment);
    }


    /**
     * @param Ticket $ticket
     * @param TicketComment $comment
     * @return JsonResponse
     */
    public function destroy(Ticket $ticket, TicketComment $comment): JsonResponse
    {
        if ($comment->ticket_id !== $ticket->id) {
            return response()->json(['message' => 'Comment does not belong to the specified ticket'], 400);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'You can delete only your own comments'], 403);
        }

        broadcast(new  TicketCommentAddBroadcastEvent($comment, 'delete'))->toOthers();

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}
