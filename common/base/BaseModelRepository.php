<?php

namespace common\base;

use common\base\BaseModel;
use common\DBBroker;

abstract class BaseModelRepository
{
    /**
     * @var $db DBBroker
     */
    private $db;
    private $tableName;

    public function __construct()
    {
        $this->db = DBBroker::getInstance();
        $this->tableName = call_user_func($this->getModelClassName() . '::getTableName');
    }

    protected function getDb(): DBBroker
    {
        return $this->db;
    }

    protected function getTableName(): string
    {
        return $this->tableName;
    }

    protected abstract function getModelClassName(): string;

    protected function getModelClassInstance(): BaseModel
    {
        $modelClassName = $this->getModelClassName();
        return new $modelClassName;
    }

    public function loadById(int $id): ?BaseModel
    {
        $query = "SELECT * FROM {$this->getTableName()} WHERE id = {$id}";

        $dbRow = $this->getDb()->query($query, true);
        if (!empty($dbRow)) {
            return $this->getModelClassInstance()->populate($dbRow);
        }

        return null;
    }

    public function loadOne(string $whereCondition = null): ?BaseModel
    {
        $query = "SELECT * FROM {$this->getTableName()}";
        if (!empty($whereCondition)) {
            $query .= " WHERE {$whereCondition}";
        }

        $dbRow = $this->getDb()->query($query, true);
        if (!empty($dbRow)) {
            return $this->getModelClassInstance()->populate($dbRow);
        }

        return null;
    }

    public function load(string $whereCondition = null): array
    {

        $query = "SELECT * FROM {$this->getTableName()}";
        if (!empty($whereCondition)) {
            $query .= " WHERE {$whereCondition}";
        }

        $objectArray = array();
        $dbResult = $this->getDb()->query($query);
        foreach ($dbResult as $dbRow) {
            $objectArray[] = $this->getModelClassInstance()->populate($dbRow);
        }

        return $objectArray;
    }
}
