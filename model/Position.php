<?php

namespace model;


use common\base\BaseModel;
use modelRepository\PositionRepository;

class Position extends BaseModel
{
    protected $name;

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

    public function populate(array $dbRow): BaseModel
    {
        $this->setName($dbRow['name']);
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
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
        return '`position`';
    }

    protected function validate(): array
    {
        $errors = array();
        $this->name = trim($this->name);

        if (strlen($this->name) === 0) {
            $errors['name'][] = 'Naziv ne sme da bude prazno polje.';
        } else if (strlen($this->name) > $this->getFieldMapping()['name']['columnSize']) {
            $errors['name'][] = 'Maksimalan broj karaktera za naziv je 50.';
        } else if ($this->isDuplicateName()) {
            $errors['name'][] = 'Pozicija sa unetim nazivom već postoji.';
        }

        return $errors;
    }

    private function isDuplicateName(): bool
    {
        $positionRepository = new PositionRepository();
        $nameColumnName = $this->getFieldMapping()['name']['columnName'];
        $duplicatePosition = $positionRepository->loadOne(true, $nameColumnName . ' = ' . $this->getDb()->quote($this->name));

        if (empty($duplicatePosition)) {
            return false;
        }

        if ($this->getId() === $duplicatePosition->getId()) {
            return false;
        }

        return true;
    }
}