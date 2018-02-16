<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\OrderForm;

class OrderFormRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return OrderForm::class;
    }

    public function loadForSupplier()
    {
        $supplierId = $_SESSION['user']['id'];
        $sent = OrderForm::SENT;
        $approved = OrderForm::APPROVED;
        $canceled = OrderForm::CANCELED;
        return parent::load(true, "`supplier_id` = {$supplierId} AND (`state` = {$sent} OR `state` = {$approved} OR `state` = {$canceled})");
    }
}