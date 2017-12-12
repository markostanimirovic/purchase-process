<?php

namespace modelRepository;


use model\Administrator;

class AdministratorRepository extends UserRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Administrator::class;
    }
}