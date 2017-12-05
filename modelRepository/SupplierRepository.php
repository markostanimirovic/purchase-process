<?php

namespace modelRepository;


use model\Supplier;

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
}