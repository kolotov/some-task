<?php

declare(strict_types=1);

namespace App\Task3\Controller;

use App\Task3\Http\Response;
use App\Task3\Http\ServerRequest;
use App\Task3\Interfaces\ControllerInterface;

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


        $response = new Response(
            $request->getBody(),
            Response::HTTP_OK,
            ["Content-Type" => "application/json; charset=UTF-8"]
        );
        return $response;
    }
}
