<?php

namespace model;


use common\base\BaseModel;
use modelRepository\SupplierRepository;

class OrderForm extends BaseModel
{
    private $code;
    private $date;
    private $supplier;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setCode($dbRow['code']);
        $this->setDate($dbRow['date']);
        $this->setSupplier((new SupplierRepository())->loadById($dbRow['supplier_id']));
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'code' => array(
                    'columnName' => 'code',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getCode()
                ),
                'date' => array(
                    'columnName' => 'date',
                    'columnType' => \PDO::PARAM_STR,
                    'columnValue' => $this->getDate()
                ),
                'supplier' => array(
                    'columnName' => 'supplier_id',
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
        return 'order_form';
    }

    protected function validate(): array
    {
        return [];
    }
}