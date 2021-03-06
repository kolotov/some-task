<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Entity\User;
use App\Task3\Exception\UnauthorizedException;
use App\Task3\Http\AuthSuccessResponse;
use App\Task3\Http\Cookie;
use App\Task3\Http\ServerRequest;
use App\Task3\Service\Database\UserRepository;
use JsonException;

class AuthService
{
    private bool $isSessionStarted = false;
    private ?string $token = null;

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

        $this->token = ($_SESSION['token'] ?? null);
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
        $clientToken = $this->request->getCookies()['token'] ?? null;
        return (
            $clientToken === $this->token &&
            !is_null($clientToken) &&
            !is_null($this->token)
        );
    }

    /**
     * User authentication
     *
     * @param string $login
     * @param string $plainPassword
     * @return AuthSuccessResponse
     * @throws JsonException
     * @throws UnauthorizedException
     */
    public function auth(string $login, string $plainPassword): AuthSuccessResponse
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }

        $user = $this->repository->loadByIdentifier($login);

        if (!$this->hasher->verify($user->getPassword(), $plainPassword)) {
            throw new UnauthorizedException('Wrong password');
        }

        $this->token = $this->createToken();
        $_SESSION['username'] = $login;
        $_SESSION['token'] = $this->token;

        return new AuthSuccessResponse([Cookie::createAuth('token', $this->token)]);
    }

    /**
     * logout user
     */
    public function logout(): void
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }
        $this->clearCookie();
        $this->endSession();
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

    /**
     * Clear user cookies
     */
    private function clearCookie(): void
    {
        if (isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
            setcookie('token', '', -1, '/');
        }

        if (isset($_COOKIE['username'])) {
            unset($_COOKIE['username']);
            setcookie('username', '', -1, '/');
        }
    }

    /**
     * @throws UnauthorizedException
     */
    public function onlyAuthorized(): void
    {
        if (!$this->isAuthorised()) {
            throw new UnauthorizedException("No access");
        }
    }
}
