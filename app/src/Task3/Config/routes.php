<?php

declare(strict_types=1);

use App\Task3\Controller\HomePageController;
use App\Task3\Controller\IncrementController;
use App\Task3\Controller\JoinRequestController;
use App\Task3\Controller\AuthController;
use App\Task3\Kernel;

/**
 * Configure routes
 */
return static function (Kernel $kernel): Kernel {
    $kernel
        ->route('/', 'GET', HomePageController::class)
        ->route('/auth', 'POST', AuthController::class)
        ->route('/increment', 'GET', IncrementController::class);
    return $kernel;
};
