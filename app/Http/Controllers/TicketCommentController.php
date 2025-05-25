<?php

namespace App\Http\Controllers;

use App\Events\TicketCommentAddBroadcastEvent;
use App\Http\Requests\TicketCommentCreateRequest;
use App\Http\Resources\TicketCommentResource;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    use AuthorizesRequests;

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
     * @throws AuthorizationException
     */
    public function destroy(Ticket $ticket, TicketComment $comment): JsonResponse
    {
        if ($comment->ticket_id !== $ticket->id) {
            return response()->json(['message' => 'Comment does not belong to this ticket'], 403);
        }
        $this->authorize('destroy', $comment);
        broadcast(new  TicketCommentAddBroadcastEvent($comment, 'delete'))->toOthers();
        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
