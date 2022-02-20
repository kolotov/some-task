<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Service\ContentBuilder;

class HomePageController implements ControllerInterface
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
