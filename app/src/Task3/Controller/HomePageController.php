<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Entity\User;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Service\AuthService;
use App\Task3\Service\ContentBuilder;
use App\Task3\Service\Database\UserRepository;
use App\Task3\Service\HasherService;

class HomePageController extends ContentBuilder implements ControllerInterface
{
    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $auth = new AuthService($request, new UserRepository(), new HasherService());
        $user =  $auth->getUser();

        if (null === $user) {
            return $this
                ->template('main.html')
                ->set('title', 'Join/login')
                ->render();
        }

        return $this
            ->template('counter.html')
            ->set('title', 'Welcome' . $user->getUsername())
            ->set('username', $user->getUsername())
            ->render();
    }
}
