<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Http\Response;
use App\Task3\Http\JsonResponse;
use App\Task3\Interfaces\ResponseInterface;
use App\Task3\Kernel;
use JsonException;

/**
 * Template processor
 */
class ContentBuilder
{
    private string $content = '';
    private array $tokens = [];
    private string $path;

    public function __construct()
    {
        $this->path = Kernel::getAppDir() . 'Template/';
    }

    /**
     * Load template
     *
     * @param string $template
     * @return ContentBuilder
     */
    public function template(string $template): ContentBuilder
    {
        $template = file_get_contents($this->path . $template);
        $this->content = empty($this->content) ?
            $template :
            $this->replace($this->content, ['content' => $template]);

        return $this;
    }

    /**
     * Extends template
     *
     * @param string $template
     * @return $this
     */
    public function extends(string $template): ContentBuilder
    {
        $extends = file_get_contents($this->path . $template);
        $this->content = $this
            ->replace($extends, ['content' => $this->content]);
        return $this;
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
     * Parse tokens
     *
     * @param string $content
     * @return array
     */
    private function parseTokens(string $content): array
    {
        $matches = [];
        preg_match_all("/{{(.*?)}}/s", $content, $matches);
        return $matches[1];
    }

    /**
     * Remove empty tokens
     *
     * @param string $content
     * @return string
     */
    private function removeEmptyTokens(string $content): string
    {
        $tokensList = $this->parseTokens($content);
        $tokens = array_map(fn() => '', array_flip($tokensList));
        return $this->replace($content, $tokens, false);
    }

    /**
     * Replace tokens to value
     *
     * @param string $content
     * @param array $tokens
     * @param bool $keepEmpty
     * @return string
     */
    private function replace(string $content, array $tokens, bool $keepEmpty = true): string
    {
        array_walk(
            $tokens,
            function ($value, $token) use (&$content, $keepEmpty) {
                if ((!empty($value) || !$keepEmpty)) {
                    $content = str_replace("{{{$token}}}", $value, $content);
                }
            }
        );
        return $content;
    }

    /**
     * Build content
     *
     * @param string $content
     * @param array $tokens
     * @return string
     */
    private function build(string $content, array $tokens): string
    {
        return $this
            ->removeEmptyTokens($this->replace($content, $tokens));
    }

    /**
     * Render content
     *
     * @return ResponseInterface
     */
    public function render(): ResponseInterface
    {
        return new Response(
            $this->build($this->content, $this->tokens),
            Response::HTTP_OK,
            ["Cache-Control" => "no-cache"]
        );
    }

    /**
     * Render json content
     *
     * @param array $data
     * @return ResponseInterface
     * @throws JsonException
     */
    public function renderJson(array $data): ResponseInterface
    {
        return new JsonResponse(
            $data,
            Response::HTTP_OK,
            ["Cache-Control" => "no-cache"]
        );
    }
}
