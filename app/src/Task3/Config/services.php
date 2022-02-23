<?php

declare(strict_types=1);

/**
 * Configure services
 */

use App\Task3\Kernel;
use App\Task3\Service\AuthService;
use App\Task3\Service\ContentBuilder;
use App\Task3\Service\Database\PdoDataBase;
use App\Task3\Service\Database\Repository;
use App\Task3\Service\Database\UserRepository;
use App\Task3\Service\HasherService;

return static fn(Kernel $kernel): Kernel => $kernel
    ->addService(PdoDataBase::class)
    ->addService(Repository::class)
    ->addService(UserRepository::class)
    ->addService(HasherService::class)
    ->addService(AuthService::class)
    ->addService(ContentBuilder::class);
