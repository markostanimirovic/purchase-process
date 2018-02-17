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

    public function getNumberOfSaved(): int
    {
        $saved = OrderForm::SAVED;
        return count(parent::load(true, "`state` = {$saved}"));
    }

    public function getNumberOfSent(): int
    {
        $sent = OrderForm::SENT;
        return count(parent::load(true, "`state` = {$sent}"));
    }

    public function getNumberOfReversed(): int
    {
        $reversed = OrderForm::REVERSED;
        return count(parent::load(true, "`state` = {$reversed}"));
    }

    public function getNumberOfApproved(): int
    {
        $approved = OrderForm::APPROVED;
        return count(parent::load(true, "`state` = {$approved}"));
    }

    public function getNumberOfCanceled(): int
    {
        $canceled = OrderForm::CANCELED;
        return count(parent::load(true, "`state` = {$canceled}"));
    }
}