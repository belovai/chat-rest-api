<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AlreadyFriendsException extends HttpException
{
    public function __construct()
    {
        parent::__construct(409, 'Already friends.');
    }
}
