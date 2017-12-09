<?php

namespace controller;

class IndexController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
    }

    public function indexAction()
    {
        $menu = $this->render('menu/admin_menu.php');
        echo $this->render('global/index.php', array('menu' => $menu));
    }
}