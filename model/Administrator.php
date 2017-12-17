<?php

namespace model;


use common\base\BaseModel;

class Administrator extends User
{
    protected $name;
    protected $surname;

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

    public function populate(array $dbRow): BaseModel
    {
        $this->setName($dbRow['administrator_name']);
        $this->setSurname($dbRow['administrator_surname']);
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'name' => array(
                    'columnName' => '`administrator_name`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getName()
                ),
                'surname' => array(
                    'columnName' => '`administrator_surname`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getSurname()
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
            $errors['name'][] = 'Maksimalan broj karaktera za prezime je 50.';
        } else if (strlen(strpbrk($this->surname, '1234567890')) > 0) {
            $errors['name'][] = 'Prezime ne sme da sadrži cifre.';
        }

        return array_merge($errors, parent::validate());
    }
}