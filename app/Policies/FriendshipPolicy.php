<?php

namespace App\Policies;

use App\Enums\FriendshipStatusEnum;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FriendshipPolicy
{
    public function viewMessages(User $user, Friendship $friendship): Response
    {
        if ($user->id !== $friendship->user_id_small && $user->id !== $friendship->user_id_big) {
            return Response::deny('Not part of this friendship.');
        }

        if ($friendship->status !== FriendshipStatusEnum::Accepted) {
            return Response::deny('Friendship not accepted.');
        }

        return Response::allow();
    }

    public function createMessage(User $user, Friendship $friendship): Response
    {
        if ($user->id !== $friendship->user_id_small && $user->id !== $friendship->user_id_big) {
            return Response::deny('Not part of this friendship.');
        }

        if ($friendship->status !== FriendshipStatusEnum::Accepted) {
            return Response::deny('Friendship not accepted.');
        }

        return Response::allow();
    }
}
