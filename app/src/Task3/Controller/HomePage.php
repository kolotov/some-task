<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Kernel\Core\ContentBuilder;
use App\Task3\Kernel\Core\ControllerInterface;
use App\Task3\Kernel\Core\Response;
use App\Task3\Kernel\Core\ServerRequest;

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
