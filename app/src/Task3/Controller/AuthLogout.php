<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Service\AuthService;
use App\Task3\Service\ContentBuilder;
use JsonException;

/**
 * User logout
 */
class AuthLogout implements ControllerInterface
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
     * @throws JsonException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $this->auth->logout();
        return $this->template->renderJson(['status' => 'ok']);
    }
}
