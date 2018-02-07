<?php

namespace adapter;

use common\base\BaseAdapter;
use model\Product;
use model\User;
use modelRepository\SupplierRepository;

class ProductAdapter extends BaseAdapter
{
    protected $supplier;

    protected function getApiUrl(): string
    {
        $supplierRepository = new SupplierRepository();
        if ($_SESSION['user']['role'] !== User::SUPPLIER) {
            throw new \Exception('Role must be SUPPLIER!');
        }
        return $supplierRepository->loadById((int)$_SESSION['user']['id'])->getApiUrl();
    }

    public function getAll(bool $assoc = false): array
    {
        try {
            $response = $this->client->request('GET', $this->url);
        } catch (\Exception $e) {
            return [];
        }

        $productsAssoc = json_decode($response->getBody()->getContents(), true);

        if ($assoc) {
            $size = sizeof($productsAssoc);
            for ($i = 0; $i < $size; $i++) {
                unset($productsAssoc[$i]['id']);
            }
            return $productsAssoc;
        }

        $products = array();
        foreach ($productsAssoc as $product) {
            $products[] = $this->convertProductAssocToClass($product);
        }

        return $products;
    }

    private function convertProductAssocToClass(array $assoc): Product
    {
        $product = new Product();

        $product->setCode($assoc['code']);
        $product->setName($assoc['name']);
        $product->setUnit($assoc['unit']);
        $product->setPrice($assoc['price']);

        return $product;
    }
}