<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendshipResource;
use App\Models\Friendship;
use App\Models\User;
use App\Services\FriendshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function __construct(private readonly FriendshipService $service)
    {
        //
    }

    public function index()
    {
        //
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $friendship = $this->service->request($request->user(), $user);

        return response()->json(new FriendshipResource($friendship), 201);
    }

    public function accept(Request $request, Friendship $friendship)
    {
        $friendship = $this->service->accept($request->user(), $friendship);

        return response()->json(new FriendshipResource($friendship));
    }

    public function destroy(Request $request, Friendship $friendship): JsonResponse
    {
        $this->service->destroy($request->user(), $friendship);

        return response()->json(status: 204);
    }

    public function block()
    {
        //
    }
}
