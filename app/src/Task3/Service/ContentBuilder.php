<?php

declare(strict_types=1);

namespace App\Task3\Service;

use App\Task3\Http\Response;
use App\Task3\Kernel;
use JsonException;

/**
 * Template processor
 */
class ContentBuilder
{
    private string $content = '';
    private array $tokens = [];

    /**
     * @param string $template
     * @return ContentBuilder
     */
    public function template(string $template): ContentBuilder
    {
        $path = Kernel::getAppDir() . 'Template/' . $template;
        $this->content = file_get_contents($path);
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
     * Build content
     *
     * @param $content
     * @return string
     */
    private function build($content): string
    {
        array_walk(
            $this->tokens,
            function ($value, $token) use ($content) {
                $content = str_replace("{{{$token}}}", $value, $content);
            }
        );
        return $content;
    }

    /**
     * Render content
     *
     * @return Response
     */
    public function render(): Response
    {
        return new Response(
            $this->build($this->content),
            Response::HTTP_OK,
            ["Content-Type" => "text/html; charset=UTF-8", "Cache-Control" => "no-cache"]
        );
    }

    /**
     * Render json content
     *
     * @param array $json
     * @return Response
     * @throws JsonException
     */
    public function renderJson(array $json): Response
    {
        return new Response(
            json_encode($json, JSON_THROW_ON_ERROR),
            Response::HTTP_OK,
            ["Content-Type" => "application/json; charset=UTF-8", "Cache-Control" => "no-cache"]
        );
    }
}
