<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Kernel\ContentBuilder;
use App\Task3\Kernel\ControllerInterface;
use App\Task3\Kernel\Response;
use App\Task3\Kernel\ServerRequest;

class HomePage implements ControllerInterface
{
    public function handle(ServerRequest $request): Response
    {
        $mainPage = new ContentBuilder('main.html');
        $content = $mainPage->set('content', 'test')->set('title', 'test task')->build();
        return new Response(
            $content,
            Response::HTTP_OK
        );
    }
}
