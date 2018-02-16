<?php

namespace model;


use common\base\BaseModel;
use modelRepository\OrderFormRepository;
use modelRepository\SupplierRepository;

class OrderForm extends BaseModel
{
    const SAVED = 1;
    const SENT = 2;
    const REVERSED = 3;
    const APPROVED = 4;
    const CANCELED = 5;

    protected $code;
    protected $date;
    protected $totalAmount;
    protected $supplier;
    protected $state;
    protected $items;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
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

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
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

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setCode($dbRow['code']);
        $this->setDateFromDb($dbRow['date']);
        $this->setTotalAmount($dbRow['total_amount']);
        $this->setSupplier((new SupplierRepository())->loadById($dbRow['supplier_id']));
        $this->setState($dbRow['state']);
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'code' => array(
                    'columnName' => '`code`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 20,
                    'columnValue' => $this->getCode()
                ),
                'date' => array(
                    'columnName' => '`date`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 10,
                    'columnValue' => $this->getDate()
                ),
                'totalAmount' => array(
                    'columnName' => '`total_amount`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 12,
                    'columnValue' => $this->getTotalAmount()
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
        return '`order_form`';
    }

    protected function validate(): array
    {
        $errors = array();

        $this->code = trim($this->code);

        if (strlen($this->code) === 0 || strlen($this->code) > 20) {
            $errors[] = 'Šifra kataloga nije u dobrom formatu.';
        } else if ($this->isDuplicateCode()) {
            $errors[] = 'Narudžbenica sa unetom šifrom već postoji.';
        }

        if (empty($this->date)) {
            $errors[] = 'Datum nije u dobrom formatu.';
        }

        if (empty($this->items)) {
            $errors[] = 'Narudžbenica mora imati najmanje jednu stavku.';
        }

        return $errors;
    }

    private function isDuplicateCode(): bool
    {
        $orderFormRepository = new OrderFormRepository();
        $codeColumnName = '`code`';

        $quotedCode = $this->getDb()->quote($this->code);

        $duplicateOrderForm = $orderFormRepository->loadOne(true, "{$codeColumnName} = {$quotedCode}");

        if (empty($duplicateOrderForm)) {
            return false;
        }

        if ($this->getId() === $duplicateOrderForm->getId()) {
            return false;
        }
        return true;
    }

    public function saveOrderForm()
    {
        $errors = $this->validate();
        if (!empty($errors)) {
            return $errors;
        }

        try {
            $this->getDb()->startTransaction();
            $this->save(false);
            $this->setId((int)$this->getDb()->lastInsertId());
            foreach ($this->items as $item) {
                $item->setOrderForm($this);
                $item->save(false);
            }
            $this->getDb()->commit();
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            $errors[] = 'Greška prilikom čuvanja narudžbenice i stavki.';
        } finally {
            return $errors;
        }
    }
}