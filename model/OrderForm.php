<?php

namespace model;


use common\base\BaseModel;

class OrderForm extends BaseModel
{
    private $code;
    private $date;
    private $supplier;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
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

    /**
     * @param Supplier $supplier
     */
    public function setDobavljac($supplier)
    {
        $this->supplier = $supplier;
    }

    public function save(): array
    {
        // TODO: Implement save() method.
    }

    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }

    protected function validate(): array
    {
        // TODO: Implement validate() method.
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'code' => array(
                    'columnName' => 'code',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50
                ),
                'date' => array(
                    'columnName' => 'date',
                    'columnType' => \PDO::PARAM_STR
                ),
                'supplier' => array(
                    'columnName' => 'supplier_id',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return 'order_form';
    }
}