<?php

namespace common\base;

use common\DBBroker;

abstract class BaseModel
{
    const STATUS_INSERT = 1;
    const STATUS_UPDATE = 2;
    const STATUS_LOAD = 3;
    const ACTIVATE = 0;
    const DEACTIVATE = 1;
    const DEFAULT_VERSION = 1;

    private $possibleStatus = [self::STATUS_INSERT, self::STATUS_UPDATE, self::STATUS_LOAD];
    private $possibleDeactivated = [self::ACTIVATE, self::DEACTIVATE];

    private $db;

    private $id;
    private $version;
    private $deactivated;
    private $status;

    public function __construct()
    {
        $this->db = DBBroker::getInstance();
        $this->status = self::STATUS_INSERT;
        $this->deactivated = self::ACTIVATE;
        $this->version = self::DEFAULT_VERSION;
    }

    protected function getDb(): DBBroker
    {
        return $this->db;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version)
    {
        $this->version = $version;
    }

    public function getDeactivated(): int
    {
        return $this->deactivated;
    }

    public function setDeactivated(int $deactivated)
    {
        if (!in_array($deactivated, $this->possibleDeactivated)) {
            throw new \Exception('Error in setDeactivated function: Denied value for $deactivated variable.');
        }
        $this->deactivated = $deactivated;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        if (!in_array($status, $this->possibleStatus)) {
            throw new \Exception('Error in setStatus function: Denied value for $status variable.');
        }
        $this->status = $status;
    }

    public abstract function save(): array;

    public abstract function delete(): bool;

    protected abstract function validate(): array;

    public function populate(array $dbRow): BaseModel {
        $this->setId($dbRow['id']);
        $this->setVersion($dbRow['version']);
        $this->setDeactivated($dbRow['deactivated']);
        $this->setStatus(self::STATUS_LOAD);
        return $this;
    }

    public function getFieldMapping(): array
    {
        return array(
            'id' => array(
                'columnName' => 'id',
                'columnType' => \PDO::PARAM_INT,
                'columnSize' => 10
            ),
            'version' => array(
                'columnName' => 'version',
                'columnType' => \PDO::PARAM_INT,
                'columnSize' => 10,
                'columnValue' => $this->getVersion()
            ),
            'deactivated' => array(
                'columnName' => 'deactivated',
                'columnType' => \PDO::PARAM_INT,
                'columnSize' => 1,
                'columnValue' => $this->getDeactivated()
            )
        );
    }

    public static abstract function getTableName(): string;
}