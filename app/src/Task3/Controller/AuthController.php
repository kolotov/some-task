<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Entity\User;
use App\Task3\Exception\AuthenticationException;
use App\Task3\Exception\UnauthorizedException;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Service\AuthService;
use App\Task3\Service\HasherService;
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
     * @param ServerRequest $request
     * @return ResponseInterface
     * @throws JsonException
     * @throws UnauthorizedException
     * @throws AuthenticationException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $repository = new UserRepository();
        $header = new HasherService();
        $auth = new AuthService($request, $repository, $header);

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
        if ($repository->hasUser($data->username)) {
            return $auth->auth($data->username, $data->password);
        }

        /**
         * Join new user
         */
        $user = User::create($data->username, $header->hash($data->password));
        $repository->save($user);
        return $auth->auth($data->username, $data->password);
    }
}
