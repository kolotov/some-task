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

        $user = User::create($data->username, $data->password_hash);
        $user->setId($data->id);
        return $user;
    }

    /**
     * Check user exists
     *
     * @param string $identifier
     * @return bool
     */
    public function hasUser(string $identifier): bool
    {
        /** @var int $count */
        $count = $this->query(
            "SELECT count(id) FROM users WHERE username = :username"
        )
            ->bind('username', $identifier)
            ->execute()
            ->getScalarResult();
        return $count > 0;
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
