<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Entity\User;
use App\Task3\Exception\AuthenticationException;
use App\Task3\Http\ServerRequest;
use App\Task3\Service\Database\UserRepository;

class AuthService
{
    private bool $isSessionStarted = false;
    private string $token;

    /**
     * @param ServerRequest $request
     * @param UserRepository $repository
     * @param HasherService $hasher
     */
    public function __construct(
        private ServerRequest $request,
        private UserRepository $repository,
        private HasherService $hasher
    ) {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }

        $this->token = ($_SESSION['token'] ?? '');
    }

    /**
     * Get user
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if (
            $this->isAuthorised() &&
            isset($_SESSION['username'])
        ) {
            return $this
                ->repository
                ->loadByIdentifier($_SESSION['username']);
        }
        return null;
    }

    /**
     * Check what user Authorised
     *
     * @return bool
     */
    public function isAuthorised(): bool
    {
        $clientToken = $this->request->getCookies()['token'] ?? '';
        return ($clientToken === $this->token);
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
        $user = $this->repository->loadByIdentifier($login);

        if (!$this->hasher->verify($user->getPassword(), $plainPassword)) {
            throw new AuthenticationException('Wrong password');
        }
        $this->token = $this->createToken();

        if (!$this->isSessionStarted) {
            $this->startSession();
        }
        $_SESSION['username'] = $login;
        $_SESSION['token'] = $this->token;
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

    /**
     * Start session
     */
    private function startSession(): void
    {
        if (\PHP_SESSION_NONE === session_status()) {
            session_start();
        }

        $this->isSessionStarted = true;
    }

    /**
     * Start destroy
     */
    private function endSession(): void
    {
        if (\PHP_SESSION_ACTIVE === session_status()) {
            session_destroy();
            unset($_SESSION);
        }

        $this->isSessionStarted = false;
    }
}
