<?php

declare(strict_types=1);

namespace App\Task3\Service;

/**
 * Hash and verify password
 */
class HasherService
{
    /**
     * Hash password
     *
     * @param string $plaintextPassword
     * @return string
     */
    public function hash(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_DEFAULT);
    }

    /**
     * Verify password
     *
     * @param string $hashedPassword
     * @param string $plainPassword
     * @return bool
     */
    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}
