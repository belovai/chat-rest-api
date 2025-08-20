<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NotRequesterException extends HttpException
{
    public function __construct(string $msg = 'Not allowed for requester.')
    {
        parent::__construct(403, $msg);
    }
}
