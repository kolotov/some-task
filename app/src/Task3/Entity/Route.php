<?php

declare(strict_types=1);

namespace App\Task3\Entity;

/** Route */
class Route
{
    /**
     * @param string $path
     * @param string $controller
     */
    public function __construct(
        private string $path,
        private string $controller
    ) {
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
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::buildName($this->path);
    }

    /**
     * Create name path
     * @param
     * @return string
     */
    public static function buildName($path): string
    {
        $normalized = trim($path, " \/") ?: '_';
        return str_replace('/', '_', $normalized);
    }
}
