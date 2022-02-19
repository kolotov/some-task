<?php

declare(strict_types=1);

namespace App\Task3\Kernel\Core;

/**
 * Client response
 */
class Response
{
    public const HTTP_OK = 200;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public static array $statusTexts = [
        200 => 'OK',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    private array $headers;
    private string $content;
    private int $status;

    public function __construct(?string $content = '', int $status = 200, array $headers = [])
    {
        $this->headers = $headers;
        $this->setContent($content);
        $this->setStatusCode($status);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatusCode(int $status): void
    {
        $this->status = $status;
    }
}
