<?php

namespace controller;


use adapter\ProductAdapter;
use model\Product;
use model\User;
use modelRepository\ProductRepository;

class ProductController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');
        echo $this->render('product/index.php', $params);
    }

    public function getAllProductsAction()
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        $products = array();
        $adapter = new ProductAdapter();
        $products['data'] = $adapter->getAll(true);

        echo json_encode($products, JSON_UNESCAPED_UNICODE);
    }

    public function getProductByCodeAction($code)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        $code = urldecode($code);

        header('Content-type: application/json');

        $adapter = new ProductAdapter();
        $product = $adapter->getByCode($code, true);

        echo json_encode($product, JSON_UNESCAPED_UNICODE);
    }

    public function getAllByCatalogAction($id)
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id kataloga može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $productRepository = new ProductRepository();
        $products = $productRepository->load(true, "`catalog_id` = {$id}");
        $productsAssoc = array();

        foreach ($products as $product) {
            $productsAssoc[] = $this->convertProductObjectToAssocArray($product);
        }

        $productsJson = json_encode($productsAssoc);

        echo json_encode('{"type": "success", "data": ' . $productsJson . '}');
    }

    private function convertProductObjectToAssocArray(Product $product): array
    {
        return array(
            'id' => $product->getId(),
            'code' => $product->getCode(),
            'name' => $product->getName(),
            'unit' => $product->getUnit(),
            'price' => $product->getPrice()
        );
    }
}