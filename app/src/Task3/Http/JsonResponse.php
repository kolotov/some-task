<?php

declare(strict_types=1);

namespace App\Task3\Http;

use JsonException;

/**
 * Json response
 * Set json header and convert content
 */
class JsonResponse extends Response
{
    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @throws JsonException
     */
    public function __construct(array $data = [], int $status = 200, array $headers = [])
    {
        parent::__construct(json_encode($data, JSON_THROW_ON_ERROR), $status, $headers);
        $this->setHeaders(["Content-Type" => "application/json; charset=UTF-8"]);
    }
}
