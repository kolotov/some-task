<?php

declare(strict_types=1);

namespace App\Task3\Http;

/**
 * Http cookie
 */
class Cookie
{
    private string $name;
    private string $value;
    private bool $httpOnly;
    private bool $Secure;
    private string $path;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * create cookie
     *
     * @param string $name
     * @param string $value
     * @return Cookie
     */
    public static function createAuth(
        string $name,
        string $value
    ): Cookie {
        $cookie = new self($name, $value);
        $cookie->setHttpOnly(true);
        $cookie->setSecure(true);
        $cookie->setPath('/');
        return $cookie;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param bool $httpOnly
     */
    public function setHttpOnly(bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->Secure;
    }

    /**
     * @param bool $Secure
     */
    public function setSecure(bool $Secure): void
    {
        $this->Secure = $Secure;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $cookies = [];
        $cookies[] = "{$this->name}={$this->value}";
        $cookies[] = "Path={$this->path}";
        $cookies[] = $this->isSecure() ? 'Secure' : '';
        $cookies[] = $this->isHttpOnly() ? 'HttpOnly' : '';
        return implode("; ", array_filter($cookies));
    }
}
