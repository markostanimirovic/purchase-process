<?php

namespace modelRepository;


use common\base\BaseModelRepository;

class UserRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return User::class;
    }
}