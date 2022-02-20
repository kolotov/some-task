<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Entity\User;

class AuthService
{
    public function getUser(): ?User
    {
        return null;
    }

    public function authentication(User $user): bool
    {
        return false;
    }
}