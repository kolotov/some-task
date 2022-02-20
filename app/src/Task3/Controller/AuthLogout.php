<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Service\AuthService;
use App\Task3\Service\ContentBuilder;
use App\Task3\Service\Database\UserRepository;
use App\Task3\Service\HasherService;
use JsonException;

/**
 * User logout
 */
class AuthLogout extends ContentBuilder implements ControllerInterface
{
    /**
     * @throws JsonException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $repository = new UserRepository();
        $header = new HasherService();
        $auth = new AuthService($request, $repository, $header);
        $auth->logout();
        return $this->renderJson(['status' => 'ok']);
    }
}
