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

        echo json_encode($products);
    }
}