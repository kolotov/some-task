<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Entity\User;
use App\Task3\Exception\AuthenticationException;
use App\Task3\Http\ServerRequest;
use App\Task3\Service\Database\UserRepository;

class AuthService
{
    private string $token;

    /**
     * @param ServerRequest $request
     */
    public function __construct(private ServerRequest $request)
    {
    }

    public function getUser(): ?User
    {
        return null;
    }

    /**
     * User authentication
     *
     * @param string $login
     * @param string $plainPassword
     * @return $this
     * @throws AuthenticationException
     */
    public function authentication(string $login, string $plainPassword): self
    {
        $repository = new UserRepository();
        $user = $repository->loadByIdentifier($login);

        $hasher = new HasherService();
        if (!$hasher->verify($user->getPassword(), $plainPassword)) {
            throw new AuthenticationException('Wrong password');
        }

        $this->token = $this->createToken();
        setcookie('token', $this->token, httponly:true);
        return $this;
    }

    /**
     * Create token
     *
     * @return string
     */
    private function createToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
