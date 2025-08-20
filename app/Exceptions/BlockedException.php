<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BlockedException extends HttpException
{
    public function __construct()
    {
        parent::__construct(403, 'Blocked.');
    }
}
