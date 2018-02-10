<?php

namespace model;


use adapter\ProductAdapter;
use common\base\BaseModel;
use modelRepository\CatalogRepository;
use modelRepository\SupplierRepository;

class Catalog extends BaseModel
{
    const SAVED = 1;
    const SENT = 2;
    const REVERSED = 3;

    protected $code;
    protected $name;
    protected $date;
    protected $supplier;
    protected $state;

    protected $productCodes;

    public function __construct()
    {
        parent::__construct();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(string $date)
    {

        $date = \DateTime::createFromFormat('d/m/Y', $date);
        if (!empty($date)) {
            $this->date = $date->format('Y-m-d');
        }
    }

    public function setDateFromDb(string $date)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $date);
        $this->date = $date->format('d/m/Y');
    }

    public function getSupplier()
    {
        return $this->supplier;
    }

    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getProductCodes()
    {
        return $this->productCodes;
    }

    public function setProductCodes($productCodes)
    {
        $this->productCodes = $productCodes;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setCode($dbRow['code']);
        $this->setName($dbRow['name']);
        $this->setDateFromDb($dbRow['date']);
        $this->setState($dbRow['state']);
        $this->setSupplier((new SupplierRepository())->loadById($dbRow['supplier_id']));
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'code' => array(
                    'columnName' => '`code`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 10,
                    'columnValue' => $this->getCode()
                ),
                'name' => array(
                    'columnName' => '`name`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                ),
                'date' => array(
                    'columnName' => '`date`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 10,
                    'columnValue' => $this->getDate()
                ),
                'supplier' => array(
                    'columnName' => '`supplier_id`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getSupplier()->getId()
                ),
                'state' => array(
                    'columnName' => '`state`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 1,
                    'columnValue' => $this->getState()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return '`catalog`';
    }

    protected function validate(): array
    {
        $errors = array();

        $this->code = trim($this->code);
        $this->name = trim($this->name);

        if (strlen($this->code) === 0 || strlen($this->code) > 10) {
            $errors[] = 'Šifra kataloga nije u dobrom formatu.';
        } else if ($this->isDuplicateCode()) {
            $errors[] = 'Katalog sa unetom šifrom već postoji.';
        }

        if (strlen($this->name) === 0 || strlen($this->name) > 50) {
            $errors[] = 'Naziv kataloga nije u dobrom formatu.';
        }

        if (empty($this->date)) {
            $errors[] = 'Datum nije u dobrom formatu.';
        }

        if (empty($this->productCodes)) {
            $errors[] = 'Katalog mora imati najmanje jedan proizvod.';
        }

        return $errors;
    }

    private function isDuplicateCode(): bool
    {
        $catalogRepository = new CatalogRepository();
        $codeColumnName = '`code`';

        $quotedCode = $this->getDb()->quote($this->code);

        $duplicateCatalog = $catalogRepository->loadOne(true, "{$codeColumnName} = {$quotedCode} AND supplier_id = {$_SESSION['user']['id']}");

        if (empty($duplicateCatalog)) {
            return false;
        }

        if ($this->getId() === $duplicateCatalog->getId()) {
            return false;
        }
        return true;
    }

    public function insertDraft(): array
    {
        $errors = array();

        $result1 = $this->validate();
        $result2 = $this->checkIfExistsProducts($this->productCodes);
        $errors = array_merge($errors, $result1, $result2);
        if (!empty($errors)) {
            return $errors;
        }

        try {
            $this->getDb()->startTransaction();
            $this->save(false);
            $this->setId((int)$this->getDb()->lastInsertId());
            foreach ($this->productCodes as $productCode) {
                $product = new Product();
                $product->setCode($productCode);
                $product->setCatalog($this);
                $product->save(false);
            }

            $this->getDb()->commit();

        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            $errors[] = 'Greška prilikom čuvanja kataloga i proizvoda.';
        } finally {
            return $errors;
        }
    }

    public function insertSent(): array
    {
        $errors = array();

        $result1 = $this->validate();
        $result2 = $this->checkIfExistsProducts($this->productCodes);
        $errors = array_merge($errors, $result1, $result2);
        if (!empty($errors)) {
            return $errors;
        }

        $adapter = new ProductAdapter();

        try {
            $this->getDb()->startTransaction();
            $this->save(false);
            $this->setId((int)$this->getDb()->lastInsertId());
            foreach ($this->productCodes as $productCode) {
                $product = $adapter->getByCode($productCode);
                $product->setCatalog($this);
                $product->save(false);
            }

            $this->getDb()->commit();

        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            $errors[] = 'Greška prilikom čuvanja kataloga i proizvoda.';
        } finally {
            return $errors;
        }
    }

    private function checkIfExistsProducts(array $productCodes)
    {
        $errors = array();
        $adapter = new ProductAdapter();

        foreach ($productCodes as $productCode) {
            if (empty($adapter->getByCode($productCode, true))) {
                $errors[] = "Proizvod sa šifrom {$productCode} ne postoji.";
            }
        }

        return $errors;
    }
}