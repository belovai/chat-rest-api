<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Friendship
 */
class FriendshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($request->user()->id == $this->user_id_small ? $this->userBig : $this->userSmall),
            'status' => $this->status,
            'requested_by' => new UserResource($this->requestedBy),
            'messages_count' => $this->messages()->count(),
        ];
    }
}
