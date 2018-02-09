<?php

namespace controller;


use adapter\ProductAdapter;
use model\User;

class CatalogController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
        $this->accessDenyIfNotIn([User::SUPPLIER]);
    }

    public function insertAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');

        $adapter = new ProductAdapter();
        $params['products'] = $adapter->getAll();

        echo $this->render('catalog/insert.php', $params);
    }

    public function saveAction()
    {
        $catalog = $_POST['catalog'];
        header('Content-type: application/json');

        if (!$this->isCatalogValidFormat($catalog)) {
            echo json_encode('{"type": "error", "message": "Poslati podaci nisu u odgovarajućem formatu."}',
                JSON_UNESCAPED_UNICODE);

            exit();
        }

        echo json_encode($catalog);
    }

    public function sendAction()
    {

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