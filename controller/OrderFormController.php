<?php

namespace controller;


use model\OrderForm;
use model\OrderFormItem;
use model\Supplier;
use model\User;
use modelRepository\OrderFormItemRepository;
use modelRepository\OrderFormRepository;
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
        $this->accessDenyIfNotIn([User::SUPPLIER, User::EMPLOYEE]);

        if ($_SESSION['user']['role'] === User::SUPPLIER) {
            $this->showSentOrderForms();
            exit();
        }

        $params = array();
        $params['menu'] = $this->render('menu/employee_menu.php');

        $orderFormRepository = new OrderFormRepository();
        $orderForms = $orderFormRepository->load(true);

        foreach ($orderForms as $orderForm) {
            $state = $orderForm->getState();
            if ($state === OrderForm::SAVED) {
                $orderForm->setState('U pripremi');
            } else if ($state === OrderForm::SENT) {
                $orderForm->setState('Poslata');
            } else if ($state === OrderForm::REVERSED) {
                $orderForm->setState('Stornirana');
            } else if ($state === OrderForm::APPROVED) {
                $orderForm->setState('Odobrena');
            } else {
                $orderForm->setState('Odbijena');
            }
        }

        $params['orderForms'] = $orderForms;

        echo $this->render('orderForm/index.php', $params);
    }

    public function showSentOrderForms()
    {
        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');

        $orderFormRepository = new OrderFormRepository();
        $orderForms = $orderFormRepository->loadForSupplier();

        foreach ($orderForms as $orderForm) {
            $state = $orderForm->getState();
            if ($state === OrderForm::SENT) {
                $orderForm->setState('Poslata');
            } else if ($state === OrderForm::APPROVED) {
                $orderForm->setState('Odobrena');
            } else {
                $orderForm->setState('Odbijena');
            }
        }

        $params['orderForms'] = $orderForms;

        echo $this->render('orderForm/show_sent.php', $params);
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
        $result = $orderForm->insertOrderFormWithItems();

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
        $result = $orderForm->insertOrderFormWithItems();

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

    public function viewAction($id)
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id narudžbenice može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderFormRepository = new OrderFormRepository();
        $orderForm = $orderFormRepository->loadById((int)$id);

        if (empty($orderForm)) {
            echo json_encode('{"type": "error", "message": "Narudžbenica sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($orderForm->getState() === OrderForm::SAVED) {
            echo json_encode('{"type": "error", "message": "Narudžbenica je u stanju U pripremi."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderFormItemRepository = new OrderFormItemRepository();
        $items = $orderFormItemRepository->getAllItemsByOrderForm($orderForm->getId());

        $itemsAssoc = array();
        foreach ($items as $item) {
            $itemsAssoc[] = $this->getItemAssoc($item);
        }
        $itemsJson = json_encode($itemsAssoc);
        $supplierAssoc = $this->getSupplierAssoc($orderForm->getSupplier());
        $supplierJson = json_encode($supplierAssoc);

        echo json_encode('{"type": "success", "orderForm": { "code": "' . $orderForm->getCode() . '", "date": "' . $orderForm->getDate() .
            '", "totalAmount": "' . $orderForm->getTotalAmount() . '", "supplier": ' . $supplierJson .
            ', "items": ' . $itemsJson . '}}', JSON_UNESCAPED_UNICODE);
    }

    public function viewForSupplierAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id narudžbenice može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderFormRepository = new OrderFormRepository();
        $orderForm = $orderFormRepository->loadById((int)$id);

        if (empty($orderForm) || $_SESSION['user']['id'] != $orderForm->getSupplier()->getId()) {
            echo json_encode('{"type": "error", "message": "Narudžbenica sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($orderForm->getState() !== OrderForm::SENT && $orderForm->getState() !== OrderForm::CANCELED &&
            $orderForm->getState() !== OrderForm::APPROVED) {
            echo json_encode('{"type": "error", "message": "Narudžbenica sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderFormItemRepository = new OrderFormItemRepository();
        $items = $orderFormItemRepository->getAllItemsByOrderForm($orderForm->getId());

        $itemsAssoc = array();
        foreach ($items as $item) {
            $itemsAssoc[] = $this->getItemAssoc($item);
        }
        $itemsJson = json_encode($itemsAssoc);

        echo json_encode('{"type": "success", "orderForm": { "code": "' . $orderForm->getCode() . '", "date": "' . $orderForm->getDate() .
            '", "totalAmount": "' . $orderForm->getTotalAmount() . '", "items": ' . $itemsJson . '}}', JSON_UNESCAPED_UNICODE);
    }

    public function approveAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id narudžbenice može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderFormRepository = new OrderFormRepository();
        $orderForm = $orderFormRepository->loadById((int)$id);
        if (empty($orderForm) || $_SESSION['user']['id'] != $orderForm->getSupplier()->getId()) {
            echo json_encode('{"type": "error", "message": "Narudžbenica sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($orderForm->getState() !== OrderForm::SENT) {
            echo json_encode('{"type": "error", "message": "Narudžbenica nije u stanju Poslata."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderForm->setDate($orderForm->getDate());
        $orderForm->setState(OrderForm::APPROVED);
        $orderForm->save(false);

        $_SESSION['message'] = $this->render('global/alert.php',
            array('type' => 'success',
                'alertText' => "<strong>Uspešno</strong> ste odobrili narudžbenicu {$orderForm->getCode()}!"));

        echo json_encode('{"type": "success", "message": "Narudžbenica je uspešno odobrena."}',
            JSON_UNESCAPED_UNICODE);
    }

    public function cancelAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id narudžbenice može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderFormRepository = new OrderFormRepository();
        $orderForm = $orderFormRepository->loadById((int)$id);
        if (empty($orderForm) || $_SESSION['user']['id'] != $orderForm->getSupplier()->getId()) {
            echo json_encode('{"type": "error", "message": "Narudžbenica sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($orderForm->getState() !== OrderForm::SENT) {
            echo json_encode('{"type": "error", "message": "Narudžbenica nije u stanju Poslata."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $orderForm->setDate($orderForm->getDate());
        $orderForm->setState(OrderForm::CANCELED);
        $orderForm->save(false);

        $_SESSION['message'] = $this->render('global/alert.php',
            array('type' => 'success',
                'alertText' => "<strong>Uspešno</strong> ste odbili narudžbenicu {$orderForm->getCode()}!"));

        echo json_encode('{"type": "success", "message": "Narudžbenica je uspešno odbijena."}',
            JSON_UNESCAPED_UNICODE);
    }

    private function getItemAssoc(OrderFormItem $item): array
    {
        return array(
            'code' => $item->getProduct()->getCode(),
            'name' => $item->getProduct()->getName(),
            'unit' => $item->getProduct()->getUnit(),
            'price' => $item->getProduct()->getPrice(),
            'quantity' => $item->getQuantity(),
            'amount' => $item->getAmount()
        );
    }

    private function getSupplierAssoc(Supplier $supplier): array
    {
        return array(
            'pib' => $supplier->getPib(),
            'name' => $supplier->getName(),
            'street' => $supplier->getStreet(),
            'streetNumber' => $supplier->getStreetNumber(),
            'placeZipCode' => $supplier->getPlace()->getZipCode(),
            'placeName' => $supplier->getPlace()->getName()
        );
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