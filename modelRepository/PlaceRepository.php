<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\Place;
use model\Supplier;

class PlaceRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Place::class;
    }

    public function isPlaceSelectedInSupplier(int $placeId)
    {
        $placeTableName = Place::getTableName();
        $supplierTableName = Supplier::getTableName();
        $query = "SELECT s.id FROM {$placeTableName} AS p JOIN {$supplierTableName} AS s " .
            "ON (p.id = s.supplier_place_id) WHERE p.id = {$placeId} AND s.deactivated = 0";
        $result = $this->getDb()->query($query, true);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public function loadByFilter(string $filter): array
    {
        $filter = $this->getDb()->quote("%$filter%");
        $whereCondition = "`name` LIKE {$filter} OR `zip_code` LIKE {$filter}";
        $places = $this->load(true, $whereCondition);
        return $places;
    }
}