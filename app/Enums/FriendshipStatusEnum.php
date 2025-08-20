<?php

namespace App\Enums;

enum FriendshipStatusEnum: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Blocked = 'blocked';
}
