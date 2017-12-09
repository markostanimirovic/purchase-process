<?php

namespace controller;



class ErrorController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        $menu = $this->render('menu/admin_menu.php');
        echo $this->render('global/404.php', array('menu' => $menu));
    }
}