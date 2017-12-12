<?php

namespace model;


use common\base\BaseModel;

class Administrator extends User
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
        $this->setName($dbRow['administrator_name']);
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
        return [];
    }
}