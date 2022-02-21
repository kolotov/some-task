<?php

/**
 * Configure routes
 */

declare(strict_types=1);

use App\Task3\Controller\AuthLogout;
use App\Task3\Controller\HomePageController;
use App\Task3\Controller\IncrementController;
use App\Task3\Controller\AuthController;
use App\Task3\Kernel;

return static function (Kernel $kernel): Kernel {
    $kernel
        ->route('/', 'GET', HomePageController::class)
        ->route('/auth', 'POST', AuthController::class)
        ->route('/logout', 'GET', AuthLogout::class)
        ->route('/increment', 'PUT', IncrementController::class)
        ->route('/increment', 'GET', IncrementController::class);
    return $kernel;
};
