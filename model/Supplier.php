<?php

namespace model;


use common\base\BaseModel;
use modelRepository\PlaceRepository;
use modelRepository\SupplierRepository;

class Supplier extends User
{
    protected $name;
    protected $pib;
    protected $street;
    protected $streetNumber;
    protected $place;

    public function __construct()
    {
        parent::__construct();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getPib()
    {
        return $this->pib;
    }

    public function setPib(string $pib)
    {
        $this->pib = $pib;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet(string $street)
    {
        $this->street = $street;
    }

    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace($place)
    {
        $this->place = $place;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setName($dbRow['supplier_name']);
        $this->setPib($dbRow['supplier_pib']);
        $this->setStreet($dbRow['supplier_street']);
        $this->setStreetNumber($dbRow['supplier_street_number']);
        $this->setPlace((new PlaceRepository())->loadById($dbRow['supplier_place_id']));
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'name' => array(
                    'columnName' => '`supplier_name`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                ),
                'pib' => array(
                    'columnName' => '`supplier_pib`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 9,
                    'columnValue' => $this->getPib()
                ),
                'street' => array(
                    'columnName' => '`supplier_street`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getStreet()
                ),
                'streetNumber' => array(
                    'columnName' => '`supplier_street_number`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 10,
                    'columnValue' => $this->getStreetNumber()
                ),
                'place' => array(
                    'columnName' => '`supplier_place_id`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getPlace()->getId()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return parent::getTableName();
    }

    protected function validate(): array
    {
        $errors = array();

        $this->name = trim($this->name);
        $this->pib = trim($this->pib);
        $this->street = trim($this->street);
        $this->streetNumber = trim($this->streetNumber);

        if (strlen($this->name) === 0) {
            $errors['name'][] = 'Ime ne sme da bude prazno polje.';
        } else if (strlen($this->name) > 50) {
            $errors['name'][] = 'Maksimalan broj karaktera za ime je 50.';
        } else if (strlen(strpbrk($this->name, '1234567890')) > 0) {
            $errors['name'][] = 'Ime ne sme da sadrži cifre.';
        }

        if (strlen($this->pib) === 0) {
            $errors['pib'][] = 'PIB ne sme da bude prazno polje.';
        } else if (!ctype_digit($this->pib)) {
            $errors['pib'][] = 'PIB može da sadrži samo cifre.';
        } else if (ctype_digit($this->pib) && strlen($this->pib) !== 9) {
            $errors['pib'][] = 'PIB mora imati tačno 9 cifara.';
        } else if ($this->isDuplicatePib()) {
            $errors['pib'][] = 'Dobavljač sa unetim PIB-om već postoji.';
        }

        if (strlen($this->street) === 0) {
            $errors['street'][] = 'Ulica ne sme da bude prazno polje.';
        } else if (strlen($this->street) > 50) {
            $errors['street'][] = 'Maksimalan broj karaktera za ulicu je 50.';
        }

        if (strlen($this->streetNumber) === 0) {
            $errors['streetNumber'][] = 'Broj ne sme da bude prazno polje. Ako je adresa dobavljača bez broja unesite BB.';
        } else if (strlen($this->streetNumber) > 10) {
            $errors['streetNumber'][] = 'Maksimalan broj karaktera za broj je 10.';
        }

        if (empty($this->place)) {
            $errors['place'][] = 'Izaberite mesto. Ako traženo ne postoji dodajte ga klikom na sledeći <a href="/place/insert/" style="color: #007bff">link</a>.';
        }

        return array_merge($errors, parent::validate());
    }

    public function isDuplicatePib(): bool
    {
        $supplierRepository = new SupplierRepository();
        $pibColumnName = '`supplier_pib`';
        $duplicateSupplier = $supplierRepository->loadOne(true, $pibColumnName . ' = "' . $this->pib . '"');

        if (empty($duplicateSupplier)) {
            return false;
        }

        if ($this->getId() === $duplicateSupplier->getId()) {
            return false;
        }
        return true;
    }
}