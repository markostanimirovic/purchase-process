<?php

namespace controller;


use adapter\ProductAdapter;
use model\Catalog;
use model\User;
use modelRepository\SupplierRepository;

class CatalogController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
        $this->accessDenyIfNotIn([User::SUPPLIER]);
    }

    public function indexAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');

        echo $this->render('catalog/index.php', $params);
    }

    public function insertAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');

        $adapter = new ProductAdapter();
        $params['products'] = $adapter->getAll();

        echo $this->render('catalog/insert.php', $params);
    }

    public function insertDraftAction()
    {
        $catalogAssoc = $_POST['catalog'];
        header('Content-type: application/json');

        if (!$this->isCatalogValidFormat($catalogAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog = new Catalog();
        $catalog->setCode($catalogAssoc['code']);
        $catalog->setName($catalogAssoc['name']);
        $catalog->setDate($catalogAssoc['date']);
        $catalog->setSupplier((new SupplierRepository())->loadById((int)$_SESSION['user']['id']));
        $catalog->setState(Catalog::SAVED);
        $catalog->setProductCodes($catalogAssoc['productCodes']);

        $result = $catalog->insertDraft();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode('{"type": "success"}');
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste sačuvali katalog {$catalog->getCode()} {$catalog->getName()}!"));
        }
    }

    public function insertSentAction()
    {
        $catalogAssoc = $_POST['catalog'];
        header('Content-type: application/json');

        if (!$this->isCatalogValidFormat($catalogAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog = new Catalog();
        $catalog->setCode($catalogAssoc['code']);
        $catalog->setName($catalogAssoc['name']);
        $catalog->setDate($catalogAssoc['date']);
        $catalog->setSupplier((new SupplierRepository())->loadById((int)$_SESSION['user']['id']));
        $catalog->setState(Catalog::SENT);
        $catalog->setProductCodes($catalogAssoc['productCodes']);

        $result = $catalog->insertSent();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode('{"type": "success"}');
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste poslali katalog {$catalog->getCode()} {$catalog->getName()}!"));
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

    private function isCatalogValidFormat($catalog): bool
    {
        if (empty($catalog)) {
            return false;
        }

        if (empty($catalog['code']) || is_array($catalog['code'])) {
            return false;
        }

        if (empty($catalog['name']) || is_array($catalog['name'])) {
            return false;
        }

        if (empty($catalog['date']) || is_array($catalog['date'])) {
            return false;
        }

        if (empty($catalog['productCodes']) || !is_array($catalog['productCodes'])) {
            return false;
        }

        foreach ($catalog['productCodes'] as $code) {
            if (empty($code) || is_array($code)) {
                return false;
            }
        }

        return true;
    }

}