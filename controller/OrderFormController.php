<?php

namespace controller;


use model\OrderForm;
use model\OrderFormItem;
use model\User;
use modelRepository\ProductRepository;
use modelRepository\SupplierRepository;

class OrderFormController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        echo 'Narudzbenica';
    }

    public function insertAction()
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        $params = array();
        $params['menu'] = $this->render('menu/employee_menu.php');

        echo $this->render('orderForm/insert.php', $params);

    }

    public function insertDraftAction()
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        $orderFormAssoc = $_POST['orderForm'];
        header('Content-type: application/json');

        if (!$this->isOrderFormValidFormatInsert($orderFormAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!ctype_digit((string)$orderFormAssoc['supplierId'])) {
            echo json_encode('{"type": "error", "messages": ["Id dobavljača može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        foreach ($orderFormAssoc['items'] as $item) {
            if (!ctype_digit((string)$item['productId'])) {
                echo json_encode('{"type": "error", "messages": ["Id proizvoda može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
                exit();
            }
            if (!ctype_digit((string)$item['quantity']) || ((int)$item['quantity']) <= 0) {
                echo json_encode('{"type": "error", "messages": ["Količina da bude samo pozitivan ceo broj."]}', JSON_UNESCAPED_UNICODE);
                exit();
            }
        }

        $orderForm = new OrderForm();
        $orderForm->setCode($orderFormAssoc['code']);
        $orderForm->setDate($orderFormAssoc['date']);

        $supplierRepository = new SupplierRepository();
        $supplier = $supplierRepository->loadById($orderFormAssoc['supplierId']);
        if (empty($supplier)) {
            echo json_encode('{"type": "error", "messages": ["Dobavljač sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }
        $orderForm->setSupplier($supplier);
        $orderForm->setState(OrderForm::SAVED);

        $productRepository = new ProductRepository();
        $orderFormItems = array();
        $totalAmount = 0;
        foreach ($orderFormAssoc['items'] as $item) {
            $orderFormItem = new OrderFormItem();
            $orderFormItem->setQuantity($item['quantity']);

            $product = $productRepository->loadById($item['productId']);
            if (empty($product)) {
                echo json_encode('{"type": "error", "messages": ["Proizvod sa id-jem' . $item['productId'] .
                    'ne postoji."]}', JSON_UNESCAPED_UNICODE);
                exit();
            }
            $orderFormItem->setProduct($product);
            $orderFormItem->setQuantity($item['quantity']);
            $amount = $product->getPrice() * $orderFormItem->getQuantity();
            $totalAmount += $amount;
            $orderFormItem->setAmount($amount);
            $orderFormItems[] = $orderFormItem;
        }
        $orderForm->setItems($orderFormItems);
        $orderForm->setTotalAmount($totalAmount);
        $result = $orderForm->saveOrderForm();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success',
                    'alertText' => "<strong>Uspešno</strong> ste sačuvali narudžbenicu {$orderForm->getCode()}!"));

            echo json_encode('{"type": "success", "message": "Narudžbenica je uspešno sačuvana."}',
                JSON_UNESCAPED_UNICODE);
        }
    }

    public function insertSentAction()
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        $orderFormAssoc = $_POST['orderForm'];
        header('Content-type: application/json');

        if (!$this->isOrderFormValidFormatInsert($orderFormAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!ctype_digit((string)$orderFormAssoc['supplierId'])) {
            echo json_encode('{"type": "error", "messages": ["Id dobavljača može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        foreach ($orderFormAssoc['items'] as $item) {
            if (!ctype_digit((string)$item['productId'])) {
                echo json_encode('{"type": "error", "messages": ["Id proizvoda može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
                exit();
            }
            if (!ctype_digit((string)$item['quantity']) || ((int)$item['quantity']) <= 0) {
                echo json_encode('{"type": "error", "messages": ["Količina da bude samo pozitivan ceo broj."]}', JSON_UNESCAPED_UNICODE);
                exit();
            }
        }

        $orderForm = new OrderForm();
        $orderForm->setCode($orderFormAssoc['code']);
        $orderForm->setDate($orderFormAssoc['date']);

        $supplierRepository = new SupplierRepository();
        $supplier = $supplierRepository->loadById($orderFormAssoc['supplierId']);
        if (empty($supplier)) {
            echo json_encode('{"type": "error", "messages": ["Dobavljač sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }
        $orderForm->setSupplier($supplier);
        $orderForm->setState(OrderForm::SENT);

        $productRepository = new ProductRepository();
        $orderFormItems = array();
        $totalAmount = 0;
        foreach ($orderFormAssoc['items'] as $item) {
            $orderFormItem = new OrderFormItem();
            $orderFormItem->setQuantity($item['quantity']);

            $product = $productRepository->loadById($item['productId']);
            if (empty($product)) {
                echo json_encode('{"type": "error", "messages": ["Proizvod sa id-jem' . $item['productId'] .
                    'ne postoji."]}', JSON_UNESCAPED_UNICODE);
                exit();
            }
            $orderFormItem->setProduct($product);
            $orderFormItem->setQuantity($item['quantity']);
            $amount = $product->getPrice() * $orderFormItem->getQuantity();
            $totalAmount += $amount;
            $orderFormItem->setAmount($amount);
            $orderFormItems[] = $orderFormItem;
        }
        $orderForm->setItems($orderFormItems);
        $orderForm->setTotalAmount($totalAmount);
        $result = $orderForm->saveOrderForm();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success',
                    'alertText' => "<strong>Uspešno</strong> ste poslali narudžbenicu {$orderForm->getCode()}!"));

            echo json_encode('{"type": "success", "message": "Narudžbenica je uspešno poslata."}',
                JSON_UNESCAPED_UNICODE);
        }
    }

    private function convertArrayToStringForJson($array): string
    {
        $items = '';
        foreach ($array as $item) {
            $items .= '"' . $item . '", ';
        }
        $items = substr($items, 0, -2);
        return $items;
    }

    private function isOrderFormValidFormatInsert(array $orderForm): bool
    {
        if (empty($orderForm)) {
            return false;
        }
        if (empty($orderForm['code']) || is_array($orderForm['code'])) {
            return false;
        }
        if (empty($orderForm['date']) || is_array($orderForm['date'])) {
            return false;
        }
        if (empty($orderForm['supplierId']) || is_array($orderForm['supplierId'])) {
            return false;
        }
        if (empty($orderForm['catalogId']) || is_array($orderForm['catalogId'])) {
            return false;
        }
        if (empty($orderForm['items']) || !is_array($orderForm['items'])) {
            return false;
        }
        foreach ($orderForm['items'] as $item) {
            if (empty($item)) {
                return false;
            }
            if (empty($item['productId']) || is_array($item['productId'])) {
                return false;
            }
            if (empty($item['quantity']) || is_array($item['quantity'])) {
                return false;
            }
        }
        return true;
    }
}