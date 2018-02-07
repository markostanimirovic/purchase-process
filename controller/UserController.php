<?php

namespace controller;


use model\User;
use modelRepository\AdministratorRepository;
use modelRepository\EmployeeRepository;
use modelRepository\PlaceRepository;
use modelRepository\PositionRepository;
use modelRepository\SupplierRepository;

class UserController extends LoginController
{
    function __construct()
    {
        $this->notLoggedIn();
    }

    public function profileAction()
    {
        if ($_SESSION['user']['role'] === User::ADMINISTRATOR) {
            $this->adminProfile();
        } else if ($_SESSION['user']['role'] === User::EMPLOYEE) {
            $this->employeeProfile();
        } else {
            $this->supplierProfile();
        }
    }

    private function adminProfile()
    {
        $params = array();

        $params['menu'] = $this->render('menu/admin_menu.php');

        $administratorRepository = new AdministratorRepository();
        $administrator = $administratorRepository->loadById($_SESSION['user']['id']);

        $params['administrator'] = $administrator;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $administrator->setName($_POST['name']);
            $administrator->setSurname($_POST['surname']);
            $administrator->setUsername($_POST['username']);
            $administrator->setEmail($_POST['email']);
            $administrator->setOldPassword($_POST['old-password']);
            $administrator->setNewPassword($_POST['new-password']);
            $administrator->setNewRepeatedPassword($_POST['new-repeated-password']);
            $result = $administrator->save();
            if (!empty($result)) {
                $params['errors'] = $result;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom izmene profila!'));
            } else {
                $_SESSION['user']['username'] = $administrator->getUsername();
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili profil!"));
                header('Location: /');
                exit();
            }
        }

        echo $this->render('user/admin_profile.php', $params);
    }

    private function employeeProfile()
    {
        $params = array();

        $params['menu'] = $this->render('menu/employee_menu.php');

        $employeeRepository = new EmployeeRepository();
        $employee = $employeeRepository->loadById($_SESSION['user']['id']);

        $params['employee'] = $employee;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee->setName($_POST['name']);
            $employee->setSurname($_POST['surname']);

            $positionId = (!isset($_POST['position']) || !ctype_digit((string)$_POST['position'])) ? -1 : $_POST['position'];
            $positionRepository = new PositionRepository();
            $position = $positionRepository->loadById($positionId);
            $employee->setPosition($position);

            $employee->setOldPassword($_POST['old-password']);
            $employee->setNewPassword($_POST['new-password']);
            $employee->setNewRepeatedPassword($_POST['new-repeated-password']);
            $result = $employee->save();
            if (!empty($result)) {
                $params['errors'] = $result;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom izmene profila!'));
            } else {
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili profil!"));
                header('Location: /');
                exit();
            }
        }

        echo $this->render('user/employee_profile.php', $params);
    }

    private function supplierProfile()
    {
        $params = array();

        $params['menu'] = $this->render('menu/supplier_menu.php');

        $supplierRepository = new SupplierRepository();
        $supplier = $supplierRepository->loadById($_SESSION['user']['id']);

        $params['supplier'] = $supplier;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplier->setName($_POST['name']);
            $supplier->setPib($_POST['pib']);
            $supplier->setStreet($_POST['street']);
            $supplier->setStreetNumber($_POST['street-number']);

            $placeId = (!isset($_POST['place']) || !ctype_digit((string)$_POST['place'])) ? -1 : $_POST['place'];
            $placeRepository = new PlaceRepository();
            $place = $placeRepository->loadById($placeId);
            $supplier->setPlace($place);

            $supplier->setApiUrl($_POST['api-url']);

            $supplier->setOldPassword($_POST['old-password']);
            $supplier->setNewPassword($_POST['new-password']);
            $supplier->setNewRepeatedPassword($_POST['new-repeated-password']);
            $result = $supplier->save();
            if (!empty($result)) {
                $params['errors'] = $result;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom izmene profila!'));
            } else {
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili profil!"));
                header('Location: /');
                exit();
            }
        }

        echo $this->render('user/supplier_profile.php', $params);
    }
}