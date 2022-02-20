<?php

declare(strict_types=1);

namespace App\Task3\Http;

class AuthSuccessResponse extends JsonResponse
{
    public function __construct(array $cookies)
    {
        parent::__construct(['status' => 'ok']);
        foreach ($cookies as $cookie) {
            $this->setCookie((string)$cookie);
        }
    }
}
