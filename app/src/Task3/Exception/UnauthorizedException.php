<?php

declare(strict_types=1);

namespace App\Task3\Exception;

use App\Task3\Http\Response;
use App\Task3\Interfaces\ExceptionInterface;
use Exception;

class UnauthorizedException extends Exception implements ExceptionInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct($message, Response::HTTP_UNAUTHORIZED);
    }
}
