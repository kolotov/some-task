<?php

declare(strict_types=1);

namespace App\Task3\Interfaces;

use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;

interface ControllerInterface
{
    public function handle(ServerRequest $request): ResponseInterface;
}