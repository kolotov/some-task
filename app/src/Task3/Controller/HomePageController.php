<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Service\{AuthService, ContentBuilder};

/**
 * Home page
 */
class HomePageController implements ControllerInterface
{
    /**
     * @param AuthService $auth
     * @param ContentBuilder $template
     */
    public function __construct(
        private AuthService $auth,
        private ContentBuilder $template
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $user =  $this->auth->getUser();

        if (null === $user) {
            return $this->template
                ->template('main.html')
                ->extends('base.html')
                ->set('title', 'Join/login')
                ->render();
        }

        return $this->template
            ->template('counter.html')
            ->set('title', 'Welcome' . $user->getUsername())
            ->set('counter', (string)$user->getCounter())
            ->set('username', $user->getUsername())
            ->render();
    }
}
