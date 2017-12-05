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

    public function loadById(int $id, bool $onlyActive = true): ?BaseModel
    {
        $query = "SELECT * FROM {$this->getTableName()} WHERE id = {$id}";

        if ($onlyActive) {
            $query .= " AND deactivated = 0";
        }

        $dbRow = $this->getDb()->query($query, true);
        if (!empty($dbRow)) {
            return $this->getModelClassInstance()->populate($dbRow);
        }

        return null;
    }

    public function loadOne(bool $onlyActive = true, string $whereCondition = null): ?BaseModel
    {
        $query = "SELECT * FROM {$this->getTableName()}";

        if ($onlyActive) {
            $query .= " WHERE deactivated = 0";
        }

        if (!empty($whereCondition) && $onlyActive) {
            $query .= " AND {$whereCondition}";
        } else if (!empty($whereCondition) && !$onlyActive) {
            $query .= " WHERE {$whereCondition}";
        }

        $dbRow = $this->getDb()->query($query, true);
        if (!empty($dbRow)) {
            return $this->getModelClassInstance()->populate($dbRow);
        }

        return null;
    }

    public function load(bool $onlyActive = true, string $whereCondition = null): array
    {

        $query = "SELECT * FROM {$this->getTableName()}";
        if ($onlyActive) {
            $query .= " WHERE deactivated = 0";
        }

        if (!empty($whereCondition) && $onlyActive) {
            $query .= " AND {$whereCondition}";
        } else if (!empty($whereCondition) && !$onlyActive) {
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
