<?php

namespace model;


use common\base\BaseModel;

class Product extends BaseModel
{
    protected $code;
    protected $name;
    protected $unit;
    protected $price;

    public function __construct()
    {
        parent::__construct();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
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
                'code' => array(
                    'columnName' => '`code`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 10,
                    'columnValue' => $this->getCode()
                ),
                'name' => array(
                    'columnName' => '`name`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                ),
                'unit' => array(
                    'columnName' => '`unit`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getUnit()
                ),
                'price' => array(
                    'columnName' => '`price`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 12,
                    'columnValue' => $this->getPrice()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return '`product`';
    }

    protected function validate(): array
    {
        return [];
    }
}