<?php

declare(strict_types=1);

namespace App\Task1;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Parse handler
 *
 * @param string $content
 * @return array
 *
 */
function parseTags(string $content): array
{
    $tags = parse($content);

    validate($content);

    return array_reduce($tags, static function ($tags, $tag) {
        return array_merge($tags, $tag);
    }, []);
}

/**
 * Parse tags
 *
 * @param $content
 * @return mixed
 */
function parse($content): array
{
    $pattern = "/\[\s*([^\/]\w+|\w+:[\w\s]*)].*?\[\s*\/\w+\s*]/s";
    preg_match_all($pattern, $content, $matches);

    return array_map(static fn ($tag) => buildTag($tag), $matches[0]);
}

/**
 * Builds a tag
 *
 * @param string $content
 * @return array[]
 */
function buildTag(string $content): array
{
    $pattern = "/\[\s*([^\/]\w+|\w+:[\w\s]*)](.*?)\[\s*\/(\w+)\s*]/s";
    preg_match($pattern, $content, $matches);
    [, $tag, $data, $close] = $matches;
    $attrs = explode(':', $tag);
    $name = trim($attrs[0]);
    $desc = trim($attrs[1] ?? '');

    if ($name !== $close) {
        throw new UnexpectedValueException();
    }

    return [
        $name => [
            'description' => $desc,
            'data' => trim($data),
        ]
    ];
}


/**
 * Validation content
 *
 * @param string $content
 * @return false|void
 */
function validate(string $content)
{
    preg_match_all("/\[\s*(\/?\w+|\w+:[\w\s]*)\s*]/s", $content, $matches);

    $normalizedTags = array_map(
        static fn ($tag) => explode(':', $tag)[0],
        $matches[1]
    );

    $openTags = array_filter(
        $normalizedTags,
        static fn ($tag) => false === str_contains($tag, '/')
    );

    $filteredClose = array_filter(
        $normalizedTags,
        static fn ($tag) => false !== str_contains($tag, '/')
    );

    $closeTags = array_map(
        static fn ($tag) => str_replace('/', '', $tag),
        $filteredClose
    );

    /**
     * if not couple for tag
     */
    if (!empty(array_diff($openTags, $closeTags))) {
        throw new InvalidArgumentException("Syntax error");
    }

    /**
     * If duplicates
     */
    $duplicates = array_filter(
        array_count_values($openTags),
        static fn ($count) => $count > 1
    );

    if (!empty($duplicates)) {
        throw new InvalidArgumentException("Syntax error");
    }
}
