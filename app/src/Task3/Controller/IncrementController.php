<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Exception\{NotFoundException, UnauthorizedException};
use App\Task3\Http\{JsonResponse, ServerRequest};
use App\Task3\Interfaces\{ControllerInterface, ResponseInterface};
use App\Task3\Service\AuthService;
use App\Task3\Service\Database\UserRepository;
use JsonException;

/**
 * Counter
 */
class IncrementController implements ControllerInterface
{
    public function __construct(
        private UserRepository $repository,
        private AuthService $auth
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnauthorizedException
     * @throws JsonException
     * @throws NotFoundException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $this->auth->onlyAuthorized();

        $user = $this->auth->getUser();

        if ('PUT' === $request->getMethod()) {
            $this->repository->updateCounter($user);
            return new JsonResponse(['status' => 'ok']);
        }

        if ('GET' === $request->getMethod()) {
            $counter = $this->repository->getCounter($user);
            return new JsonResponse(['status' => 'ok', 'result' => $counter]);
        }

        throw new NotFoundException();
    }
}