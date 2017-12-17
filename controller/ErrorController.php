<?php

namespace controller;


use model\User;

class ErrorController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        if ($_SESSION['user']['role'] === User::ADMINISTRATOR) {
            $menu = $this->render('menu/admin_menu.php');
        } else if ($_SESSION['user']['role'] === User::EMPLOYEE) {
            $menu = $this->render('menu/employee_menu.php');
        } else if ($_SESSION['user']['role'] === User::SUPPLIER) {
            $menu = $this->render('menu/supplier_menu.php');
        }

        echo $this->render('global/404.php', array('menu' => $menu));
    }
}