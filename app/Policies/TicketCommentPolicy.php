<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;


class TicketCommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, TicketComment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

}
