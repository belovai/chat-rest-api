<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendshipResource;
use App\Models\Friendship;
use App\Models\User;
use App\Services\FriendshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FriendshipController extends Controller
{
    public function __construct(private readonly FriendshipService $service)
    {
        //
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $friendships = $this->service->friendships($request->user())
            ->with('userSmall', 'userBig', 'requestedBy', 'blockedBy', 'messages')
            ->whereIn('status', ['accepted', 'pending'])
            ->paginate();

        return FriendshipResource::collection($friendships);
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $friendship = $this->service->request($request->user(), $user);

        return (new FriendshipResource($friendship))->response()->setStatusCode(201);
    }

    public function accept(Request $request, Friendship $friendship): FriendshipResource
    {
        $friendship = $this->service->accept($request->user(), $friendship);

        return new FriendshipResource($friendship);
    }

    public function destroy(Request $request, Friendship $friendship): JsonResponse
    {
        $this->service->destroy($request->user(), $friendship);

        return response()->json(status: 204);
    }

    public function block(Request $request, Friendship $friendship): FriendshipResource
    {
        $friendship = $this->service->block($request->user(), $friendship);

        return new FriendshipResource($friendship);
    }
}
