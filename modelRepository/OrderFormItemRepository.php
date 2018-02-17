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

    public function getCatalogIdByOrderFormItem($orderForm) {
        $id = $orderForm->getId();
        $query = "SELECT `catalog_id` FROM `order_form_item` AS ofi JOIN `product` AS prod ON (ofi.product_id = prod.id) WHERE " .
            "ofi.id = {$id}";

        $catalogId = $this->getDb()->query($query, true);
        return $catalogId['catalog_id'];
    }
}