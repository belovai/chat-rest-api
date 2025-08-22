<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Friendship;
use App\Services\FriendshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageController extends Controller
{
    public function __construct(private readonly FriendshipService $service)
    {
        //
    }

    public function index(Request $request, Friendship $friendship): AnonymousResourceCollection
    {
        $this->service->authorizeMessaging($request->user(), $friendship);

        $messages = $friendship->messages()->with('sender')->paginate();

        return MessageResource::collection($messages);
    }

    public function store(MessageRequest $request, Friendship $friendship): JsonResponse
    {
        $this->service->authorizeMessaging($request->user(), $friendship);

        $message = $friendship->messages()->create(
            [
                'sender_id' => $request->user()->id,
                'content' => $request->validated('content'),
            ]
        );

        return response()->json(new MessageResource($message), 201);
    }
}
