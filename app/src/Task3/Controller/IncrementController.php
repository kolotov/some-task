<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Interfaces\ResponseInterface;

class IncrementController implements ControllerInterface
{
    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        // TODO: Implement handle() method.
        return new Response('$content');
    }
}
