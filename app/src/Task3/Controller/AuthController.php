<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Entity\User;
use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Service\Database\UserRepository;
use App\Task3\Service\HasherService;
use JsonException;

/**
 * Join and Login User
 */
class AuthController extends ContentBuilder implements ControllerInterface
{
    /**
     * @param ServerRequest $request
     * @return Response
     * @throws JsonException
     * @throws JsonException
     */
    public function handle(ServerRequest $request): Response
    {
        $data = json_decode($request->getBody(), flags: JSON_THROW_ON_ERROR);

        $header = new HasherService();
        $user = User::create(
            $data->username,
            $header->hash($data->password)
        );

        if ($repository->hasUser($user->getUsername())) {
            //TODO: Auth
        }

        $repository->save($user);
        return $this->renderJson(['status' => 'ok']);
    }
}
