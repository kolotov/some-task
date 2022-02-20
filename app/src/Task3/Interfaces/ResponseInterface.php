<?php

declare(strict_types=1);

namespace App\Task3\Interfaces;

/**
 * Client response Interface
 */
interface ResponseInterface
{
    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @param string $content
     */
    public function setContent(string $content): void;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @param int $status
     */
    public function setStatusCode(int $status): void;

    /**
     * @param string $cookie
     */
    public function setCookie(string $cookie): void;

    /**
     * @return array
     */
    public function getCookies(): array;
}
