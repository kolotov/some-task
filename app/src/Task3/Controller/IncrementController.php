<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Exception\NotFoundException;
use App\Task3\Exception\UnauthorizedException;
use App\Task3\Http\JsonResponse;
use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Service\AuthService;
use App\Task3\Service\Database\UserRepository;
use App\Task3\Service\HasherService;
use JsonException;

/**
 * Counter
 */
class IncrementController implements ControllerInterface
{
    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws JsonException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $repository = new UserRepository();
        $auth = new AuthService($request, $repository, new HasherService());
        $auth->onlyAuthorized();

        $user = $auth->getUser();

        if ('PUT' === $request->getMethod()) {
            $repository->updateCounter($user);
            return new JsonResponse(['status' => 'ok']);
        }

        if ('GET' === $request->getMethod()) {
            $counter = $repository->getCounter($user);
            return new JsonResponse(['status' => 'ok', 'result' => $counter]);
        }

        throw new NotFoundException();
    }
}
