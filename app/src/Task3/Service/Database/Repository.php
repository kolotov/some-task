<?php

declare(strict_types=1);

namespace App\Task3\Service\Database;

use PDO;
use PDOStatement;

class Repository
{
    private PDOStatement $stmt;
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = PdoDataBase::create();
    }

    /**
     * @param string $sql
     * @return Repository
     */
    public function query(string $sql): Repository
    {
        $this->stmt = $this->pdo->prepare($sql);
        return $this;
    }

    /**
     * @param $param
     * @param $value
     * @return Repository
     */
    public function bind($param, $value): Repository
    {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;

        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * @return Repository
     */
    public function execute(): Repository
    {
        $this->stmt->execute();
        return $this;
    }

    /**
     * @param false $assoc
     * @return bool|array
     */
    public function getAll(bool $assoc = false): bool|array
    {
        return $this->stmt->fetchAll(($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ));
    }

    /**
     * @param bool $assoc
     * @return mixed
     */
    public function getRow(bool $assoc = false): mixed
    {
        return $this->stmt->fetch(($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ));
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->stmt->rowCount();
    }
}