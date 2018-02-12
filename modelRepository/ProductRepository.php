<?php

namespace modelRepository;


use common\base\BaseModelRepository;
use model\Product;

class ProductRepository extends BaseModelRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClassName(): string
    {
        return Product::class;
    }

    public function getAllProductCodesByCatalog(int $catalogId): array
    {
        $query = "SELECT `code` FROM `product` WHERE `catalog_id` = {$catalogId}";
        $codesAssoc = $this->getDb()->query($query);

        $codes = array();
        foreach ($codesAssoc as $codeAssoc) {
            $codes[] = $codeAssoc['code'];
        }
        return $codes;
    }

    public function deleteIfNotIn(array $productCodes, int $catalogId)
    {
        $subquery = '';
        foreach ($productCodes as $productCode) {
            $quotedCode = $this->getDb()->quote($productCode);
            $subquery .= "`code` != {$quotedCode} AND ";
        }


        if (!empty($subquery)) {
            $subquery = substr($subquery, 0, -5);
            $subquery = ' AND ' . $subquery;
        }

        $query = "SELECT `id` FROM `product` WHERE `catalog_id` = {$catalogId}{$subquery}";
        $idsAssoc = $this->getDb()->query($query);

        foreach ($idsAssoc as $idAssoc) {
            $id = $idAssoc['id'];
            $delQuery = "DELETE FROM `product` WHERE `id` = {$id}";
            $this->getDb()->query($delQuery);
        }
    }

    public function getIdByCode($code, $catalogId): int
    {
        $quotedCode = $this->getDb()->quote($code);
        $query = "SELECT `id` FROM `product` WHERE `code` = {$quotedCode} AND `catalog_id` = {$catalogId}";
        $idAssoc = $this->getDb()->query($query, true);
        return $idAssoc['id'];
    }

    public function getAllProductCodesForInsert(array $productCodes, $catalogId): array
    {
        $codesForInsert = array();
        foreach ($productCodes as $productCode) {
            $quotedCode = $this->getDb()->quote($productCode);
            $query = "SELECT `code` FROM `product` WHERE `code` = {$quotedCode} AND `catalog_id` = {$catalogId}";
            $result = $this->getDb()->query($query);
            if (empty($result)) {
                $codesForInsert[] = $productCode;
            }
        }
        return $codesForInsert;
    }
}