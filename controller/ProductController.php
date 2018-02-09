<?php

namespace controller;


use adapter\ProductAdapter;
use model\User;

class ProductController extends LoginController
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
        echo $this->render('product/index.php', $params);
    }

    public function getAllProductsAction()
    {
        header('Content-type: application/json');

        $products = array();
        $adapter = new ProductAdapter();
        $products['data'] = $adapter->getAll(true);

        echo json_encode($products, JSON_UNESCAPED_UNICODE);
    }

    public function getProductByCodeAction($code)
    {
        $code = urldecode($code);

        header('Content-type: application/json');

        $adapter = new ProductAdapter();
        $product = $adapter->getByCode($code, true);

        echo json_encode($product, JSON_UNESCAPED_UNICODE);
    }
}