<?php

namespace model;


use common\base\BaseModel;
use modelRepository\PositionRepository;

class Employee extends User
{
    protected $name;
    protected $surname;
    protected $position;

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

    public function getSurname()
    {
        return $this->surname;
    }

    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setName($dbRow['employee_name']);
        $this->setSurname($dbRow['employee_surname']);
        $this->setPosition((new PositionRepository())->loadById($dbRow['employee_position_id']));
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'name' => array(
                    'columnName' => '`employee_name`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                ),
                'surname' => array(
                    'columnName' => '`employee_surname`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getSurname()
                ),
                'position' => array(
                    'columnName' => '`employee_position_id`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnSize' => 10,
                    'columnValue' => $this->getPosition()->getId()
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
        $this->surname = trim($this->surname);

        if (strlen($this->name) === 0) {
            $errors['name'][] = 'Ime ne sme da bude prazno polje.';
        } else if (strlen($this->name) > 50) {
            $errors['name'][] = 'Maksimalan broj karaktera za ime je 50.';
        } else if (strlen(strpbrk($this->name, '1234567890')) > 0) {
            $errors['name'][] = 'Ime ne sme da sadrži cifre.';
        }

        if (strlen($this->surname) === 0) {
            $errors['surname'][] = 'Prezime ne sme da bude prazno polje.';
        } else if (strlen($this->surname) > 50) {
            $errors['surname'][] = 'Maksimalan broj karaktera za prezime je 50.';
        } else if (strlen(strpbrk($this->surname, '1234567890')) > 0) {
            $errors['surname'][] = 'Prezime ne sme da sadrži cifre.';
        }

        if (empty($this->position)) {
            if ($_SESSION['user']['role'] === User::ADMINISTRATOR) {
                $errors['position'][] = 'Izaberite poziciju. Ako tražena pozicija ne postoji dodajte je klikom na sledeći <a href="/position/insert/" style="color: #007bff">link</a>.';
            } else {
                $errors['position'][] = 'Izaberite poziciju.';
            }
        }

        return array_merge($errors, parent::validate());
    }

}