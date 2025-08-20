<?php

namespace App\Services;

use App\Enums\FriendshipStatusEnum;
use App\Exceptions\AlreadyFriendsException;
use App\Exceptions\BlockedException;
use App\Exceptions\ForbiddenActionException;
use App\Exceptions\FriendRequestExistsException;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FriendshipService
{
    /**
     * @throws \Throwable
     */
    public function request(User $actor, User $target): Friendship
    {
        if ($actor->is($target)) {
            throw new ForbiddenActionException('You cannot friend yourself.');
        }

        [$userIdSmall, $userIdBig] = $this->normalize($actor->id, $target->id);

        return DB::transaction(function () use ($actor, $userIdSmall, $userIdBig) {
            $existing = Friendship::pair($userIdSmall, $userIdBig)->lockForUpdate()->first();

            if ($existing) {
                if ($existing->status === FriendshipStatusEnum::Accepted) {
                    throw new AlreadyFriendsException;
                }
                if ($existing->status === FriendshipStatusEnum::Blocked) {
                    throw new BlockedException;
                }
                throw new FriendRequestExistsException(
                    $existing->requested_by === $actor->id ? 'You already requested this user.' : 'They already requested you.'
                );
            }

            return Friendship::create([
                'user_id_small' => $userIdSmall,
                'user_id_big' => $userIdBig,
                'status' => FriendshipStatusEnum::Pending,
                'requested_by' => $actor->id,
            ]);
        });
    }

    /**
     * @return array{int, int}
     */
    private function normalize(int $x, int $y): array
    {
        return $x < $y ? [$x, $y] : [$y, $x];
    }
}
