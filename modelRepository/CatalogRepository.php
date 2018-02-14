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

    public function load(bool $onlyActive = true, string $whereCondition = null): array
    {
        $condition = "supplier_id = {$_SESSION['user']['id']}";

        if (!is_null($whereCondition)) {
            $whereCondition .= ' AND ' . $condition;
        } else {
            $whereCondition = $condition;
        }

        return parent::load($onlyActive, $whereCondition);
    }

    public function loadForEmployee(): array
    {
        return parent::load(true, '`state` = ' . Catalog::SENT);
    }

    public function loadForEmployeeBySupplier(int $supplierId): array
    {
        return parent::load(true, '`state` = ' . Catalog::SENT . ' AND `supplier_id` = ' . $supplierId);
    }

}