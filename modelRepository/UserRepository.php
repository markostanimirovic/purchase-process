<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\User;

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

    public function getUserByUsernameOrEmail($usernameEmail): ?User
    {
        $usernameColumnName = '`username`';
        $emailColumnName = '`email`';
        $usernameEmail = $this->getDb()->quote($usernameEmail);

        $user = $this->loadOne(true, "{$usernameColumnName} = {$usernameEmail} OR {$emailColumnName} = {$usernameEmail}");
        return $user;
    }
}