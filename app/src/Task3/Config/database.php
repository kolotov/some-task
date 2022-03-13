<?php

/**
 * Configure database
 */

declare(strict_types=1);

use App\Task3\Kernel;
use App\Task3\Service\Database\PdoDataBase;

return static fn (PdoDataBase $db): PdoDataBase =>
    $db->setDsn('sqlite:' . Kernel::getAppDir() . '/../../var/db.sqlite');
