<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;

class JoinRequestController  implements ControllerInterface
{

    public function handle(ServerRequest $request): Response
    {
        // TODO: Implement handle() method.
        return new Response(
            '$content',
            Response::HTTP_OK
        );
    }
}