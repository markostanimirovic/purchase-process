<?php

namespace controller;


use model\User;
use modelRepository\AdministratorRepository;

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

        }

        echo $this->render('user/admin_profile.php', $params);
    }

    private function employeeProfile()
    {
        $menu = $this->render('menu/employee_menu.php');

        echo $this->render('user/employee_profile.php', array('menu' => $menu));
    }

    private function supplierProfile()
    {
        $menu = $this->render('menu/supplier_menu.php');

        echo $this->render('user/supplier_profile.php', array('menu' => $menu));
    }
}