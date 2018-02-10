<?php

namespace model;


use common\base\BaseModel;
use modelRepository\CatalogRepository;

class Product extends BaseModel
{
    protected $code;
    protected $name;
    protected $unit;
    protected $price;
    protected $catalog;

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

    public function getCatalog()
    {
        return $this->catalog;
    }

    public function setCatalog($catalog)
    {
        $this->catalog = $catalog;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setCode($dbRow['code']);
        $this->setName($dbRow['name']);
        $this->setPrice(floatval($dbRow['price']));
        $this->setUnit($dbRow['unit']);
        $this->setCatalog((new CatalogRepository())->loadById($dbRow['catalog_id']));
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
                    'columnSize' => 100,
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
                ),
                'catalog' => array(
                    'columnName' => '`catalog_id`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getCatalog()->getId()
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