<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Ticket.{id}', function ($user, $id) {
    return $user ;
});

