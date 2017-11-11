<?php

namespace model;


use common\base\BaseModel;

class OrderFormItem extends BaseModel
{
    protected $quantity;
    protected $orderForm;
    protected $product;

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return OrderForm
     */
    public function getOrderForm()
    {
        return $this->orderForm;
    }

    /**
     * @param OrderForm $orderForm
     */
    public function setOrderForm($orderForm)
    {
        $this->orderForm = $orderForm;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
                'quantity' => array(
                    'columnName' => 'quantity',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50
                ),
                'orderForm' => array(
                    'columnName' => 'order_form_id',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10
                ),
                'product' => array(
                    'columnName' => 'product_id',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return 'order_form_item';
    }
}