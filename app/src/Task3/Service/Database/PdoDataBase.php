<?php

declare(strict_types=1);

namespace App\Task3\Service\Database;

use App\Task3\Kernel;
use PDO;
use PDOStatement;

/**
 * PDO Database
 **/
class PdoDataBase
{
    private ?string $dsn = null;
    private ?string $user = null;
    private ?string $password = null;

    public function __construct()
    {
    }

    /**
     * Init pdo
     */
    private function initPdo(): PDO
    {
        $options = array(
            PDO::ATTR_PERSISTENT => true, //persistent connection
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //enable error mode
        );

        return new PDO(
            $this->dsn,
            $this->user,
            $this->password,
            $options
        );
    }

    /**
     * @return PDO
     */
    public static function create(): PDO
    {
        $db = new self();
        (require Kernel::getAppDir() . 'Config/database.php')($db);
        return $db->initPdo();
    }

    /**
     * set DSN for connect db
     * @param string $dsn
     * @return PdoDataBase
     */
    public function setDsn(string $dsn): PdoDataBase
    {
        $this->dsn = $dsn;
        return $this;
    }

    /**
     * Set password db
     *
     * @param string $password
     * @return PdoDataBase
     */
    public function setPassword(string $password): PdoDataBase
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Set user db
     *
     * @param string $user
     * @return PdoDataBase
     */
    public function setUser(string $user): PdoDataBase
    {
        $this->user = $user;
        return $this;
    }
}
