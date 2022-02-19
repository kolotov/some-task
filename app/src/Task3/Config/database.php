<?php

declare(strict_types=1);

use App\Task3\Kernel;
use App\Task3\Service\Database\PdoDataBase;

/**
 * Configure database
 */

return static function (PdoDataBase $db): PdoDataBase {
    $db->setDsn('sqlite:' . Kernel::getAppDir() . 'counter.db');
    return $db;
};
