<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\OrderForm;

class OrderFormItemRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return OrderForm::class;
    }
}