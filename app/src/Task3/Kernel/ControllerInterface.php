<?php

declare(strict_types=1);

namespace App\Task3\Kernel;

interface ControllerInterface
{
    public function handle(ServerRequest $request): Response;
}