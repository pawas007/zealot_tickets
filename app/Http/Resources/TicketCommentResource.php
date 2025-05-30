<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketCommentResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_name'   => $this->user?->name,
            'user_id'   => $this->user?->id,
            'text'        => $this->text,
            'created_at'  => $this->created_at,
        ];
    }
}
