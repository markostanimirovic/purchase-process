<?php

namespace controller;


use login\LoginController;
use model\User;
use model\Supplier;
use modelRepository\PlaceRepository;

class SupplierController extends LoginController
{
    public function indexAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/main_menu.php');
        echo $this->render('supplier/index.php', $params);
    }

    public function insertAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/main_menu.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplier = new Supplier();

            $supplier->setUsername($_POST['username']);
            $supplier->setEmail($_POST['email']);
            $supplier->setPassword($_POST['password']);
            $supplier->setRepeatedPassword($_POST['repeated-password']);
            $supplier->setRole(User::SUPPLIER);

            $supplier->setName($_POST['name']);
            $supplier->setPib($_POST['pib']);
            $supplier->setStreet($_POST['street']);
            $supplier->setStreetNumber($_POST['street-number']);

            $placeId = (!isset($_POST['place']) || !ctype_digit((string)$_POST['place'])) ? -1 : $_POST['place'];
            $placeRepository = new PlaceRepository();
            $place = $placeRepository->loadById($placeId);

            $supplier->setPlace($place);

            $result = $supplier->save();

            if (!empty($result)) {
                $params['errors'] = $result;
                $params['supplier'] = $supplier;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom unosa novog dobavljača!'));
            } else {
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste dodali dpbavljača {$supplier->getPib()} {$supplier->getName()}!"));
                header("Location: /supplier/");
                exit();
            }
        }

        echo $this->render('supplier/insert.php', $params);
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}