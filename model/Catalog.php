<?php

namespace model;


use common\base\BaseModel;
use modelRepository\SupplierRepository;

class Catalog extends BaseModel
{
    protected $code;
    protected $name;
    protected $date;
    protected $products;
    protected $supplier;

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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getSupplier()
    {
        return $this->supplier;
    }

    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setCode(floatval($dbRow['code']));
        $this->setName($dbRow['name']);
        $this->setDate($dbRow['date']);
        $this->setSupplier((new SupplierRepository())->loadById($dbRow['supplier_id']));
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
                'date' => array(
                    'columnName' => '`date`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 10,
                    'columnValue' => $this->getDate()
                ),
                'supplier' => array(
                    'columnName' => '`supplier_id`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getSupplier()->getId()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return '`catalog`';
    }

    protected function validate(): array
    {
        $errors = array();

        $this->code = trim($this->code);
        $this->name = trim($this->name);
        $this->date = trim($this->date);

        //TODO: implement

        return $errors;
    }
}