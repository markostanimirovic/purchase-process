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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return double
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit)
    {
        $this->unit = $unit;
    }

    public function save(): array
    {
        $result = $this->validate();
        if (!empty($result)) {
            return $result;
        }

        $attributes = $this->getFieldMapping();
        unset($attributes['id']);


        if ($this->getStatus() === self::STATUS_INSERT) {
            $result = $this->getDb()->insert(Product::getTableName(), $attributes);
        }

        if ($this->getStatus() === self::STATUS_UPDATE && $this->getStatus() === self::STATUS_LOAD) {
            $result = $this->getDb()->update(Product::getTableName(), $attributes, "id = {$this->getId()}");
        }

        if ($result !== true) {
            throw new \Exception('Error in save function class Product');
        }

        return [];
    }

    public function delete(): bool
    {
        $this->setStatus(self::STATUS_UPDATE);
        $this->setDeactivated(self::DEACTIVATE);
        $this->save();
    }

    protected function validate(): array
    {
        return [];
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
}