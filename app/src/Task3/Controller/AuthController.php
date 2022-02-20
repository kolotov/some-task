<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Entity\User;
use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;
use App\Task3\Service\Database\UserRepository;

/**
 * Join and Login User
 */
class AuthController implements ControllerInterface
{
    /**
     * @param ServerRequest $request
     * @return Response
     */
    public function handle(ServerRequest $request): Response
    {

        $data = json_decode($request->getBody());

        $user = User::create($data->username, $data->password);

        $repository = new UserRepository();

        if ($repository->hasUser($user->getUsername())) {
            //TODO: Auth
        }

        $repository->save($user);

        return new Response(
            '{"message":"ok"}',
            Response::HTTP_OK,
            ["Content-Type" => "application/json; charset=UTF-8"]
        );
    }
}
