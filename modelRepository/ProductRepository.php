<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\Product;

class ProductRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Product::class;
    }
}