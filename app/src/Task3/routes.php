<?php

declare(strict_types=1);

use App\Task3\Controller\HomePage;
use App\Task3\Kernel\Kernel;

/**
 * Configure routes
 */
return static function (Kernel $kernel): Kernel {
    $kernel->route('/', HomePage::class);
    return $kernel;
};
