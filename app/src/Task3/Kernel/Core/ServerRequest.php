<?php

declare(strict_types=1);

namespace App\Task3\Kernel\Core;

/**
 * Create request from server var
 */
class ServerRequest
{
    private string $uri;
    private string $body;
    private array $params = [];

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?: '';

        parse_str($query, $this->params);

        $this->body  = file_get_contents('php://input') ?: '';
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}
