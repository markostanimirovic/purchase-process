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

    public function isSupplierSelectedInCatalog()
    {
        //TODO: implement
    }

    public function isSupplierSelectedInProduct()
    {
        //TODO: implement
    }
}