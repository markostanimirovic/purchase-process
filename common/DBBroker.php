<?php

namespace common;

use \PDO;

class DBBroker
{
    private static $instance;
    private $config;

    /**
     * @var $pdo PDO
     */
    private $pdo;

    private function __construct()
    {
        $this->config = require(ROOT . 'config/database_config.php');
    }

    private function connect()
    {
        if (is_null($this->pdo)) {
            try {
                $this->pdo = new PDO("mysql:host={$this->config['host']};dbname={$this->config['schema']};port={$this->config['port']}",
                    $this->config['username'],
                    $this->config['password'],
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ));
            } catch (\PDOException $ex) {
                throw $ex;
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    private function disconnect()
    {
        $this->pdo = null;
    }

    public function startTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DBBroker();
            self::$instance->connect();
        }
        return self::$instance;
    }

    public function insert(string $tableName, array $attributes): bool
    {
        $columns = '';
        foreach ($attributes as $attribute => $column) {
            $columns .= $column['columnName'] . ', ';
        }

        $columns = substr($columns, 0, -2);

        $values = substr(str_repeat('?, ', count($attributes)), 0, -2);

        $stmt = $this->pdo->prepare("INSERT INTO $tableName ($columns) VALUES ($values)");

        $binded = true;
        $param = 1;
        foreach ($attributes as $attribute => $column) {
            $binded = $stmt->bindParam($param++, $column['columnValue'], $column['columnType'], !empty($column['columnSize']) ? $column['columnSize'] : null);
            if (!$binded) {
                break;
            }
        }

        try {
            if ($binded && $stmt->execute()) {
                return true;
            } else {
                throw new \Exception("Insert {$tableName}: Problem with bind parameters");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(string $tableName, array $attributes, string $condition = null): bool
    {
        $query = "UPDATE $tableName SET ";

        foreach ($attributes as $attribute => $column) {
            $query .= "{$column['columnName']} = ?, ";
        }

        $query = substr($query, 0, -2);

        if (!empty($condition)) {
            $query .= ' WHERE ' . $condition;
        }

        $stmt = $this->pdo->prepare($query);

        $binded = true;
        $param = 1;
        foreach ($attributes as $attribute => $column) {
            $binded = $stmt->bindParam($param++, $column['columnValue'], $column['columnType'], !empty($column['columnSize']) ? $column['columnSize'] : null);
            if (!$binded) {
                break;
            }
        }

        try {
            if ($binded && $stmt->execute()) {
                return true;
            } else {
                throw new \Exception("Update {$tableName}: Problem with bind parameters");
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function query($query, $loadOne = false): array
    {
        try {
            $stmt = $this->pdo->query($query);

            if ($loadOne) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $result === false ? array() : $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function quote(string $text, int $parameterType = PDO::PARAM_STR)
    {
        return $this->pdo->quote($text, $parameterType);
    }
}