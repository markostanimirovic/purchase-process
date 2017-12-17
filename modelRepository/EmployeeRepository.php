<?php

namespace modelRepository;


use model\Employee;
use model\User;

class EmployeeRepository extends UserRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Employee::class;
    }

    public function load(bool $onlyActive = true, string $whereCondition = null): array
    {
        $roleColumnName = '`role`';
        $employeeRole = User::EMPLOYEE;
        if (!empty($whereCondition)) {
            $whereCondition .= "AND {$roleColumnName} = {$employeeRole}";
        } else {
            $whereCondition = "{$roleColumnName} = {$employeeRole}";
        }
        return parent::load($onlyActive, $whereCondition);
    }
}