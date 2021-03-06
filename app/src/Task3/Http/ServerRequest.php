<?php

declare(strict_types=1);

namespace App\Task3\Http;

/**
 * Create request from server var
 */
class ServerRequest
{
    private string $uri;
    private string $body;
    private string $method;
    private array $headers = [];
    private array $cookies = [];
    private array $params = [];

    public function __construct()
    {
        $this->setUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
        $this->setBody(file_get_contents('php://input') ?: '');
        $this->setMethod($_SERVER['REQUEST_METHOD'] ?? '');
        $this->setCookies($_COOKIE);

        if (isset($_SERVER["CONTENT_TYPE"])) {
            $this->setHeaders(["Content-Type" => $_SERVER["CONTENT_TYPE"]]);
        }


        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?: '';
        parse_str($query, $this->params);
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
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
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
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     */
    public function setCookies(array $cookies): void
    {
        $this->cookies = array_merge($this->cookies, $cookies);
    }
}
