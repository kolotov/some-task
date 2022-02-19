<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Kernel\Core\ControllerInterface;
use App\Task3\Kernel\Core\Response;
use App\Task3\Kernel\Core\ServerRequest;

class HomePage implements ControllerInterface
{
    public function handle(ServerRequest $request): Response
    {
        return new Response(
            'Home Page',
            Response::HTTP_OK
        );
    }
}
