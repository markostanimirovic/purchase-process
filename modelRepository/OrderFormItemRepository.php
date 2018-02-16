<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\OrderFormItem;

class OrderFormItemRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return OrderFormItem::class;
    }

    public function getAllItemsByOrderForm($orderFormId)
    {
        return $this->load(true, "`order_form_id` = {$orderFormId}");
    }
}