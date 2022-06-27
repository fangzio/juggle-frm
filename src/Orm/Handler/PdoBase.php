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
            $startTime = now_ms();
            $this->connection = new \PDO($dsn, $this->conf['user'], $this->conf['passwd']);
            logger()->debug(sprintf('pdo connect cost:%sms', cost($startTime, now_ms())));
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->afterConnection();
            return $this->connection;
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    public function tablePrefix()
    {
        return $this->conf['table_prefix'];
    }

    public function parseSql($sql)
    {
        return $this->connection()->prepare($sql);
    }

    /**
     * @param $sql
     * @param array $bind
     * @return false|\PDOStatement
     * @throws RuntimeException
     */
    public function query($sql, $bind = [])
    {
        if ($stmt = $this->connection()->prepare($sql)) {
            $startTime = now_ms();
            $rs = $stmt->execute($bind);
            logger()->debug(sprintf("execute sql[%s], cost:%sms", $sql, cost($startTime, now_ms())));
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