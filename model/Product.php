<?php

namespace model;


use common\base\BaseModel;
use common\DBBroker;

class Product extends BaseModel
{
    private $name;
    private $price;
    private $unit;

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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit(string $unit)
    {
        $this->unit = $unit;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setName($dbRow['name']);
        $this->setPrice(floatval($dbRow['price']));
        $this->setUnit($dbRow['unit']);
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
                'price' => array(
                    'columnName' => 'price',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 12,
                    'columnValue' => $this->getPrice()
                ),
                'unit' => array(
                    'columnName' => 'unit',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 20,
                    'columnValue' => $this->getUnit()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return 'product';
    }

    protected function validate(): array
    {
        return [];
    }
}