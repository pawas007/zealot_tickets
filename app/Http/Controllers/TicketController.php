<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketCreateRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketWithMessagesResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): TicketCollection
    {
        $tickets = Ticket::query()->orderBy('created_at', 'desc')->paginate(15);
        return new TicketCollection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketCreateRequest $request)
    {
        Ticket::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Ticket created',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): TicketWithMessagesResource
    {
        return new TicketWithMessagesResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted']);
    }


    /**
     * @return JsonResponse
     */
    public function initData(): JsonResponse
    {
        $data = ['statuses' => Ticket::STATUSES];
        return response()->json($data);
    }
}
