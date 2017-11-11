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

    public function __construct()
    {
        $this->db = DBBroker::getInstance();
    }

    protected function getDb(): DBBroker
    {
        return $this->db;
    }

    protected abstract function getModelClassName(): string;

    protected function getModelClassInstance(): BaseModel
    {
        $modelClassName = $this->getModelClassName();
        return new $modelClassName;
    }

    public function load($whereCondition = null): array {
        $tableName = call_user_func($this->getModelClassName() . '::getTableName');
        $query = "SELECT * FROM {$tableName}";
        if(!empty($whereCondition)) {
            $query .= " WHERE {$whereCondition}";
        }
        $dbResult = $this->getDb()->query($query);
        $objectArray = array();
        foreach ($dbResult as $dbRow) {
            $objectArray[] = $this->getModelClassInstance()->populate($dbRow);
        }
        return $objectArray;
    }

    public function loadById($id): ?BaseModel
    {
        $tableName = call_user_func($this->getModelClassName() . '::getTableName');
        $query = "SELECT * FROM {$tableName} WHERE id = {$id}";
        return $this->getModelClassInstance()->populate($this->getDb()->query($query, true));
    }

    public function loadOne($whereCondition = null): ?BaseModel
    {
        $tableName = call_user_func($this->getModelClassName() . '::getTableName');
        $query = "SELECT * FROM {$tableName}";
        if(!empty($whereCondition)) {
            $query .= " WHERE {$whereCondition}";
        }
        return $this->getModelClassInstance()->populate($this->getDb()->query($query, true));
    }
}