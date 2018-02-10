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

    public function getAllProductCodesByCatalog(int $catalogId): array
    {
        $query = "SELECT `code` FROM `product` WHERE `catalog_id` = {$catalogId}";
        $codesAssoc = $this->getDb()->query($query);

        $codes = array();
        foreach ($codesAssoc as $codeAssoc) {
            $codes[] = $codeAssoc['code'];
        }
        return $codes;
    }
}