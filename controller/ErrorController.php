<?php

namespace controller;


use common\base\BaseController;

class ErrorController extends BaseController
{
    public function indexAction()
    {
        $menu = $this->render('menu/main_menu.php');
        echo $this->render('global/404.php', array('menu' => $menu));
    }
}