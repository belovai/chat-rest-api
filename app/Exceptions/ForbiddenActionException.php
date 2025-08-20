<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenActionException extends HttpException
{
    public function __construct(string $message = 'Forbidden')
    {
        parent::__construct(403, $message);
    }
}
