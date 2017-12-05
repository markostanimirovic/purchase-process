<?php

namespace model;


use common\base\BaseModel;
use modelRepository\PlaceRepository;

class Place extends BaseModel
{
    protected $zipCode;
    protected $name;

    public function __construct()
    {
        parent::__construct();
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setZipCode($dbRow['zip_code']);
        $this->setName($dbRow['name']);
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'zipCode' => array(
                    'columnName' => '`zip_code`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnValue' => $this->getZipCode()
                ),
                'name' => array(
                    'columnName' => '`name`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return '`place`';
    }

    protected function validate(): array
    {
        $errors = array();

        $this->zipCode = trim($this->zipCode);
        $this->name = trim($this->name);

        if (strlen($this->zipCode) === 0) {
            $errors['zipCode'][] = 'Poštanski broj ne sme da bude prazno polje.';
        } else if (!ctype_digit($this->zipCode)) {
            $errors['zipCode'][] = 'Poštanski broj može da sadrži samo cifre.';
        } else if (ctype_digit($this->zipCode) && strlen($this->zipCode) !== 5) {
            $errors['zipCode'][] = 'Poštanski broj mora imati tačno 5 cifara.';
        } else if ($this->zipCode[0] == 0) {
            $errors['zipCode'][] = 'Poštanski broj ne sme početi nulom.';
        } else if ($this->isDuplicateZipCode()) {
            $errors['zipCode'][] = 'Mesto sa unetim poštanskim brojem već postoji.';
        }

        if (strlen($this->name) === 0) {
            $errors['name'][] = 'Naziv ne sme da bude prazno polje.';
        } else if (strlen($this->name) > $this->getFieldMapping()['name']['columnSize']) {
            $errors['name'][] = 'Maksimalan broj karaktera za naziv je ' . $this->getFieldMapping()['name']['columnSize'] . '.';
        } else if (strlen(strpbrk($this->name, '1234567890')) > 0) {
            $errors['name'][] = 'Naziv ne sme da sadrži cifre.';
        }

        return $errors;
    }

    private function isDuplicateZipCode(): bool
    {
        $placeRepository = new PlaceRepository();
        $zipCodeColumnName = $this->getFieldMapping()['zipCode']['columnName'];
        $duplicatePlace = $placeRepository->loadOne(true, "{$zipCodeColumnName} = {$this->zipCode}");

        if (empty($duplicatePlace)) {
            return false;
        }

        if ($this->getId() === $duplicatePlace->getId()) {
            return false;
        }
        return true;
    }
}