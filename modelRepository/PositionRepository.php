<?php

namespace modelRepository;


use common\base\BaseModelRepository;
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
//        $positionTableName = Position::getTableName();
//        $employeeTableName = Employee::getTableName();
//        $query = "SELECT s.id FROM {$placeTableName} AS p JOIN {$supplierTableName} AS s " .
//            "ON (p.id = s.supplier_place_id) WHERE p.id = {$placeId} AND s.deactivated = 0";
//        $result = $this->getDb()->query($query, true);
//        if (empty($result)) {
//            return false;
//        }
// TODO: create Employee
//        return true;
        return false;
    }
}