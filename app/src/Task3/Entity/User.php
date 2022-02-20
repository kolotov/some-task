<?php

declare(strict_types=1);

namespace App\Task3\Entity;

/**
 * User entity
 */
class User
{
    private int $id;
    private string $username;
    private string $password;
    private int $counter;

    /**
     * @param $username
     */
    private function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Create user
     *
     * @param string $username
     * @param string|null $password
     * @return User
     */
    public static function create(string $username, ?string $password = null): User
    {
        $user = new self($username);
        $user->setPassword($password);
        return $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter(int $counter): void
    {
        $this->counter = $counter;
    }
}
