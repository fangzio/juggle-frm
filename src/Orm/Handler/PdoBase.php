<?php

namespace Juggle\Frm\Orm\Handler;

use Juggle\Frm\Exception\RuntimeException;

abstract class PdoBase extends Handler
{
    /**
     * @return \PDO
     * @throws RuntimeException
     */
    public function connection()
    {
        if (!empty($this->connection)) {
            return $this->connection;
        }
        try {
            $dsn = $this->dsn();
            $this->connection = new \PDO($dsn, $this->conf['user'], $this->conf['passwd']);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->afterConnection();
            return $this->connection;
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @param $sql
     * @param array $bind
     * @return false|\PDOStatement
     * @throws RuntimeException
     */
    public function query($sql, $bind = [])
    {
        logger()->debug(sprintf("pdo base query"));
        if ($stmt = $this->connection()->prepare($sql)) {
            $rs = $stmt->execute($bind);
            if ($rs) {
                return $stmt;
            }
            return false;
        } else {
            throw new RuntimeException('pdo prepare failed');
        }
    }

    protected function afterConnection()
    {
    }
}