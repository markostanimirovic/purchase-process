<?php

namespace model;


use common\base\BaseModel;
use modelRepository\OrderFormRepository;
use modelRepository\ProductRepository;

class OrderFormItem extends BaseModel
{
    protected $quantity;
    protected $orderForm;
    protected $product;

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
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

    public function setOrderForm(OrderForm $orderForm)
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

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setQuantity($dbRow['quantity']);
        $this->setOrderForm((new OrderFormRepository())->loadById($dbRow['order_form_id']));
        $this->setProduct((new ProductRepository())->loadById($dbRow['product_id']));
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'quantity' => array(
                    'columnName' => 'quantity',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getQuantity()
                ),
                'orderForm' => array(
                    'columnName' => 'order_form_id',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getOrderForm()->getId()
                ),
                'product' => array(
                    'columnName' => 'product_id',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getOrderForm()->getId()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return 'order_form_item';
    }

    protected function validate(): array
    {
        return [];
    }
}