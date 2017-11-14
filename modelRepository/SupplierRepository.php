<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\Supplier;

class SupplierRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Supplier::class;
    }
}