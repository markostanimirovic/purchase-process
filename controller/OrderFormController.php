<?php

namespace controller;


use model\User;

class OrderFormController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        echo 'Narudzbenica';
    }

    public function insertAction()
    {
        $this->accessDenyIfNotIn([User::EMPLOYEE]);

        $params = array();
        $params['menu'] = $this->render('menu/employee_menu.php');

        echo $this->render('orderForm/insert.php', $params);

    }
}