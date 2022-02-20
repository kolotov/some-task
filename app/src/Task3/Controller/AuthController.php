<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Entity\User;
use App\Task3\Exception\AuthenticationException;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Service\{AuthService, ContentBuilder, HasherService};
use App\Task3\Service\Database\UserRepository;
use JsonException;

/**
 * Join and Login User
 */
class AuthController extends ContentBuilder implements ControllerInterface
{
    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     * @throws JsonException
     * @throws AuthenticationException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        //TODO:: user DTO
        $data = json_decode($request->getBody(), flags: JSON_THROW_ON_ERROR);

        //TODO:: validation


        /**
         * Authentication user
         */
        $repository = new UserRepository();
        if ($repository->hasUser($data->username)) {
            $auth = new AuthService($request);
            $auth->authentication($data->username, $data->password);
            return $this->renderJson(['status' => 'ok']);
        }

        /**
         * Join new user
         */
        $header = new HasherService();
        $user = User::create($data->username, $header->hash($data->password));
        $repository->save($user);
        return $this->renderJson(['status' => 'ok']);
    }
}
