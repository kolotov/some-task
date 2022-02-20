<?php

declare(strict_types=1);

namespace App\Task3\Service\Database;

use App\Task3\Entity\User;

/**
 * Request to db for user
 */
class UserRepository extends Repository
{
    /**
     * Check if user exists
     *
     * @param string $identifier
     * @return bool
     */
    public function hasUser(string $identifier): bool
    {
        return (null !== $this->loadByIdentifier($identifier));
    }

    /**
     * @param string $identifier
     * @return User|null
     */
    public function loadByIdentifier(string $identifier): ?User
    {
        $data = $this->query(
            "SELECT * FROM users WHERE username = :username"
        )
            ->bind('username', $identifier)
            ->execute()
            ->getRow();

        if (empty($data)) {
            return null;
        }

        $user = User::create($data->username);
        $user->setCounter($data->counter);
        return $user;
    }

    /**
     * Save user
     *
     * @param User $user
     */
    public function save(User $user)
    {
        $this->query(
            "INSERT INTO users(username, password_hash) 
            VALUES (:username, :password)"
        )
            ->bind('username', $user->getUsername())
            ->bind('password', $user->getPassword())
            ->execute();
    }
}
