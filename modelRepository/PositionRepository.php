<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\Employee;
use model\Position;

class PositionRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Position::class;
    }

    public function isPositionSelectedInEmployee(int $positionId): bool
    {
        $positionTableName = Position::getTableName();
        $employeeTableName = Employee::getTableName();
        $query = "SELECT e.id FROM {$positionTableName} AS p JOIN {$employeeTableName} AS e " .
            "ON (p.id = e.employee_position_id) WHERE p.id = {$positionId} AND e.deactivated = 0";
        $result = $this->getDb()->query($query, true);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public function loadByFilter(string $filter): array
    {
        $filter = $this->getDb()->quote("%$filter%");
        $whereCondition = "`name` LIKE {$filter}";
        $positions = $this->load(true, $whereCondition);
        return $positions;
    }
}