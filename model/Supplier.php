<?php

namespace model;


use common\base\BaseModel;

class Supplier extends BaseModel
{
    private $name;
    private $adress;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param string $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }

    public function save(): array
    {

    }

    public function delete(): bool
    {
        return true;
    }

    protected function validate(): array
    {
        return [];
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'name' => array(
                    'columnName' => 'name',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50
                ),
                'adress' => array(
                    'columnName' => 'adress',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 100
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return 'supplier';
    }
}