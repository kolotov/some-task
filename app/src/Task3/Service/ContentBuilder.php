<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Kernel;

/**
 * Template parser
 */
class ContentBuilder
{
    private string $path;
    private string $content = '';
    private array $tokens = [];

    /**
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->path = Kernel::getAppDir() . 'Template/' . $template;
        $this->content = file_get_contents($this->path);
    }

    /**
     * Add token
     *
     * @param string $token
     * @param string $value
     * @return $this
     */
    public function set(string $token, string $value): ContentBuilder
    {
        $this->tokens = array_replace($this->tokens, [$token => $value]);
        return $this;
    }
    /**
     * Build content
     *
     * @return string
     */
    public function build(): string
    {
        array_walk(
            $this->tokens,
            function ($value, $token) {
                $this->content = str_replace("{{{$token}}}", $value, $this->content);
            }
        );

        return $this->content;
    }
}
