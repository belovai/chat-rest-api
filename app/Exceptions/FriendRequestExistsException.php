<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class FriendRequestExistsException extends HttpException
{
    public function __construct(string $msg = 'Friend request already exists.')
    {
        parent::__construct(409, $msg);
    }
}
