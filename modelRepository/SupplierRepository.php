<?php

namespace modelRepository;


use model\Supplier;
use model\User;

class SupplierRepository extends UserRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Supplier::class;
    }

    public function load(bool $onlyActive = true, string $whereCondition = null): array
    {
        $roleColumnName = '`role`';
        $supplierRole = User::SUPPLIER;
        if (!empty($whereCondition)) {
            $whereCondition .= "AND {$roleColumnName} = {$supplierRole}";
        } else {
            $whereCondition = "{$roleColumnName} = {$supplierRole}";
        }
        return parent::load($onlyActive, $whereCondition);
    }

    public function loadByFilter(string $filter): array
    {
        $filter = $this->getDb()->quote("%$filter%");
        $whereCondition = "`supplier_name` LIKE {$filter} OR `supplier_pib` LIKE {$filter}";
        $suppliers = $this->load(true, $whereCondition);
        return $suppliers;
    }
}