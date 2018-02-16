<?php

namespace controller;


use adapter\ProductAdapter;
use common\base\BaseModel;
use model\Catalog;
use model\User;
use modelRepository\CatalogRepository;
use modelRepository\ProductRepository;
use modelRepository\SupplierRepository;

class CatalogController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        $this->accessDenyIfNotIn([User::SUPPLIER, User::EMPLOYEE]);

        if ($_SESSION['user']['role'] === User::EMPLOYEE) {
            $this->showSentCatalogs();
            exit();
        }

        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');

        $catalogRepository = new CatalogRepository();
        $catalogs = $catalogRepository->load(true);

        foreach ($catalogs as $catalog) {
            $state = $catalog->getState();
            if ($state === Catalog::SAVED) {
                $catalog->setState('U pripremi');
            } else if ($state === Catalog::SENT) {
                $catalog->setState('Poslat');
            } else {
                $catalog->setState('Storniran');
            }
        }

        $params['catalogs'] = $catalogs;

        echo $this->render('catalog/index.php', $params);
    }

    private function showSentCatalogs()
    {
        $params = array();
        $params['menu'] = $this->render('menu/employee_menu.php');

        $catalogRepository = new CatalogRepository();
        $params['catalogs'] = $catalogRepository->loadForEmployee();

        echo $this->render('catalog/show_sent.php', $params);
    }

    public function insertAction()
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');

        $adapter = new ProductAdapter();
        $params['products'] = $adapter->getAll();

        echo $this->render('catalog/insert.php', $params);
    }

    public function insertOnExistingAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        if (!ctype_digit((string)$id)) {
            header('Location: /404NotFound/');
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id'] || $catalog->getState() === Catalog::SAVED) {
            header('Location: /404NotFound/');
        }

        $params = array();
        $params['menu'] = $this->render('/menu/supplier_menu.php');
        $params['catalog'] = $catalog;

        $adapter = new ProductAdapter();
        $params['products'] = $adapter->getAll();

        $productRepository = new ProductRepository();
        $productCodes = $productRepository->getAllProductCodesByCatalog($catalog->getId());
        $selectedProducts = array();
        foreach ($productCodes as $productCode) {
            $product = $adapter->getByCode($productCode);
            if (!empty($product)) {
                $selectedProducts[] = $product;
            }
        }
        $params['selectedProducts'] = $selectedProducts;

        echo $this->render('catalog/insert.php', $params);

    }

    public function editAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        if (!ctype_digit((string)$id)) {
            header('Location: /404NotFound/');
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id'] || $catalog->getState() !== Catalog::SAVED) {
            header('Location: /404NotFound/');
        }

        $params = array();
        $params['menu'] = $this->render('menu/supplier_menu.php');
        $params['catalog'] = $catalog;

        $adapter = new ProductAdapter();
        $params['products'] = $adapter->getAll();

        $productRepository = new ProductRepository();
        $productCodes = $productRepository->getAllProductCodesByCatalog($catalog->getId());
        $selectedProducts = array();
        foreach ($productCodes as $productCode) {
            $product = $adapter->getByCode($productCode);
            if (!empty($product)) {
                $selectedProducts[] = $product;
            }
        }
        $params['selectedProducts'] = $selectedProducts;

        echo $this->render('catalog/edit.php', $params);
    }

    public function deleteAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "messages": ["Id kataloga može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id']) {
            echo json_encode('{"type": "error", "messages": ["Katalog sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($catalog->getState() !== Catalog::SAVED) {
            echo json_encode('{"type": "error", "messages": ["Katalog nije u stanju U pripremi."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog->setDeactivated(BaseModel::DEACTIVATE);
        $catalog->setDate($catalog->getDate());
        $catalog->save(false);

        $_SESSION['message'] = $this->render('global/alert.php',
            array('type' => 'success',
                'alertText' => "<strong>Uspešno</strong> ste obrisali katalog {$catalog->getCode()} {$catalog->getName()}!"));

        echo json_encode('{"type": "success", "message": "Selektovani katalog je uspešno obrisan."}',
            JSON_UNESCAPED_UNICODE);
    }

    public function reverseAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "messages": ["Id kataloga može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id']) {
            echo json_encode('{"type": "error", "messages": ["Katalog sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($catalog->getState() !== Catalog::SENT) {
            echo json_encode('{"type": "error", "messages": ["Katalog nije u stanju Poslat."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog->setState(Catalog::REVERSED);
        $catalog->setDate($catalog->getDate());
        $catalog->save(false);

        $_SESSION['message'] = $this->render('global/alert.php',
            array('type' => 'success',
                'alertText' => "<strong>Uspešno</strong> ste stornirali katalog {$catalog->getCode()} {$catalog->getName()}!"));

        echo json_encode('{"type": "success", "message": "Selektovani katalog je uspešno storniran."}',
            JSON_UNESCAPED_UNICODE);
    }

    public function sendAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "messages": ["Id kataloga može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id']) {
            echo json_encode('{"type": "error", "messages": ["Katalog sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($catalog->getState() !== Catalog::SAVED) {
            echo json_encode('{"type": "error", "messages": ["Katalog nije u stanju U pripremi."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog->setDate($catalog->getDate());
        $catalog->setState(Catalog::SENT);

        $productRepository = new ProductRepository();
        $catalog->setProductCodes($productRepository->getAllProductCodesByCatalog($catalog->getId()));

        $result = $catalog->updateDraftToSent();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success',
                    'alertText' => "<strong>Uspešno</strong> ste poslali katalog {$catalog->getCode()} {$catalog->getName()}!"));

            echo json_encode('{"type": "success", "message": "Selektovani katalog je uspešno poslat."}',
                JSON_UNESCAPED_UNICODE);
        }
    }

    public function insertDraftAction()
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

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

    public function updateDraftAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        $catalogAssoc = $_POST['catalog'];
        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "messages": ["Id kataloga može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!$this->isCatalogValidFormat($catalogAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id']) {
            echo json_encode('{"type": "error", "messages": ["Katalog sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($catalog->getState() !== Catalog::SAVED) {
            echo json_encode('{"type": "error", "messages": ["Katalog nije u stanju U pripremi."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!$this->isCatalogValidFormat($catalogAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog->setCode($catalogAssoc['code']);
        $catalog->setName($catalogAssoc['name']);
        $catalog->setDate($catalogAssoc['date']);
        $catalog->setSupplier((new SupplierRepository())->loadById((int)$_SESSION['user']['id']));
        $catalog->setState(Catalog::SAVED);
        $catalog->setProductCodes($catalogAssoc['productCodes']);

        $result = $catalog->updateDraft();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode('{"type": "success"}');
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili katalog {$catalog->getCode()} {$catalog->getName()}!"));
        }
    }

    public function insertSentAction()
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

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

    public function updateDraftToSentAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        $catalogAssoc = $_POST['catalog'];
        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "messages": ["Id kataloga može da bude samo broj."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!$this->isCatalogValidFormat($catalogAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id']) {
            echo json_encode('{"type": "error", "messages": ["Katalog sa poslatim id-jem ne postoji."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($catalog->getState() !== Catalog::SAVED) {
            echo json_encode('{"type": "error", "messages": ["Katalog nije u stanju U pripremi."]}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!$this->isCatalogValidFormat($catalogAssoc)) {
            echo json_encode('{"type": "error", "messages": ["Greška! Poslati podaci nisu u ispravnom formatu."]}',
                JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalog->setCode($catalogAssoc['code']);
        $catalog->setName($catalogAssoc['name']);
        $catalog->setDate($catalogAssoc['date']);
        $catalog->setSupplier((new SupplierRepository())->loadById((int)$_SESSION['user']['id']));
        $catalog->setState(Catalog::SENT);
        $catalog->setProductCodes($catalogAssoc['productCodes']);

        $result = $catalog->updateDraftToSent();

        if (!empty($result)) {
            $errors = $this->convertArrayToStringForJson($result);
            echo json_encode('{"type": "error", "messages": [' . $errors . ']}', JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode('{"type": "success"}');
            $_SESSION['message'] = $this->render('global/alert.php',
                array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste poslali katalog {$catalog->getCode()} {$catalog->getName()}!"));
        }
    }

    public function viewAction($id)
    {
        $this->accessDenyIfNotIn([User::SUPPLIER]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id kataloga može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getSupplier()->getId() !== $_SESSION['user']['id']) {
            echo json_encode('{"type": "error", "message": "Katalog sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        if ($catalog->getState() === Catalog::SAVED) {
            echo json_encode('{"type": "error", "message": "Katalog je u stanju U pripremi."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $productRepository = new ProductRepository();
        $productsAssoc = $productRepository->getAllProductsAssocByCatalog($catalog->getId());
        $productsJson = json_encode($productsAssoc);

        echo json_encode('{"type": "success", "catalog": { "code": "' . $catalog->getCode() . '", "name": "'
            . $catalog->getName() . '", "date": "' . $catalog->getDate() . '", "products": '
            . $productsJson . '}}', JSON_UNESCAPED_UNICODE);
    }

    public function viewForEmployeeAction($id)
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id kataloga može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalog = $catalogRepository->loadById((int)$id);

        if (empty($catalog) || $catalog->getState() !== Catalog::SENT) {
            echo json_encode('{"type": "error", "message": "Katalog sa poslatim id-jem ne postoji."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $productRepository = new ProductRepository();
        $productsAssoc = $productRepository->getAllProductsAssocByCatalog($catalog->getId());
        $productsJson = json_encode($productsAssoc);
        $supplierAssoc = $this->convertSupplierObjectToAssocArray($catalog->getSupplier());
        $supplierJson = json_encode($supplierAssoc, JSON_UNESCAPED_UNICODE);

        echo json_encode('{"type": "success", "catalog": { "code": "' . $catalog->getCode() . '", "name": "'
            . $catalog->getName() . '", "date": "' . $catalog->getDate() . '", "supplier":' . $supplierJson . ', "products": '
            . $productsJson . '}}', JSON_UNESCAPED_UNICODE);
    }

    public function getAllBySupplierAction($id)
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        header('Content-type: application/json');

        if (!ctype_digit((string)$id)) {
            echo json_encode('{"type": "error", "message": "Id dobavljača može da bude samo broj."}', JSON_UNESCAPED_UNICODE);
            exit();
        }

        $catalogRepository = new CatalogRepository();
        $catalogs = $catalogRepository->loadForEmployeeBySupplier($id);
        $catalogsAssoc = array();

        foreach ($catalogs as $catalog) {
            $catalogsAssoc[] = $this->convertCatalogObjectToAssocArray($catalog);
        }

        $catalogsJson = json_encode($catalogsAssoc);

        echo json_encode('{"type": "success", "data": ' . $catalogsJson . '}');
    }

    private function convertCatalogObjectToAssocArray($catalog)
    {
        return array(
            'id' => $catalog->getId(),
            'code' => $catalog->getCode(),
            'name' => $catalog->getName()
        );
    }

    private function convertSupplierObjectToAssocArray($supplier)
    {
        return array(
            'name' => $supplier->getName(),
            'pib' => $supplier->getPib(),
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