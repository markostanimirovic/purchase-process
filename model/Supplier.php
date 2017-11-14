<?php

namespace model;


use common\base\BaseModel;
use common\base\BaseModelRepository;

class Supplier extends BaseModel
{
    private $name;
    private $address;

    public function __construct()
    {
        parent::__construct();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setName($dbRow['name']);
        $this->setAddress($dbRow['address']);
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'name' => array(
                    'columnName' => 'name',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                ),
                'address' => array(
                    'columnName' => 'address',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 100,
                    'columnValue' => $this->getAddress()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return 'supplier';
    }

    protected function validate(): array
    {
        return [];
    }
}