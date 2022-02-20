<?php

declare(strict_types=1);

namespace App\Task3\Http;

use App\Task3\Interfaces\ResponseInterface;

/**
 * Client response
 */
class Response implements ResponseInterface
{
    public const HTTP_OK = 200;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public static array $statusTexts = [
        200 => 'OK',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    private array $headers = [];
    private array $cookies = [];

    private string $content;
    private int $status;

    public function __construct(?string $content = '', int $status = 200, array $headers = [])
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * {@inheritDoc}
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     */
    public function setStatusCode(int $status): void
    {
        $headers = ["HTTP/1.1 {$status} " . Response::$statusTexts[$status]];
        $this->headers = array_merge($this->headers, $headers);
        $this->status = $status;
    }

    /**
     * {@inheritDoc}
     */
    public function setCookie(string $cookie): void
    {
        $this->cookies[] = "Set-Cookie: $cookie";
    }

    /**
     * {@inheritDoc}
     */
    public function getCookie(): array
    {
        return $this->cookies;
    }
}
