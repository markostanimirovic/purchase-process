<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\Catalog;

class CatalogRepository extends BaseModelRepository
{

    protected function getModelClassName(): string
    {
        return Catalog::class;
    }
}