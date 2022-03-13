<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Entity\User;
use App\Task3\Exception\{AuthenticationException, UnauthorizedException};
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Service\{AuthService, HasherService};
use App\Task3\Service\Database\UserRepository;
use Exception;
use JsonException;
use Webmozart\Assert\Assert;

/**
 * Join and Login User
 */
class AuthController implements ControllerInterface
{
    /**
     * @param AuthService $auth
     * @param UserRepository $repository
     * @param HasherService $header
     */
    public function __construct(
        private AuthService $auth,
        private UserRepository $repository,
        private HasherService $header
    ) {
    }

    /**
     * {@inheritDoc}
     * @throws JsonException
     * @throws AuthenticationException
     * @throws UnauthorizedException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {

        $data = json_decode($request->getBody(), flags: JSON_THROW_ON_ERROR);

        try {
            Assert::lengthBetween($data->username, 3, 50, 'username length must be between 3 and 50');
            Assert::lengthBetween($data->password, 3, 50, 'password length must be between 3 and 16');
        } catch (Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }

        /**
         * Authentication user
         */
        if ($this->repository->hasUser($data->username)) {
            return $this->auth->auth($data->username, $data->password);
        }

        /**
         * Join new user
         */
        $user = User::create($data->username, $this->header->hash($data->password));
        $this->repository->save($user);
        return $this->auth->auth($data->username, $data->password);
    }
}
