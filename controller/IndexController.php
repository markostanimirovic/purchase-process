<?php

namespace controller;


use common\base\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $menu = $this->render('menu/main_menu.php');
        echo $this->render('global/index.php', array('menu' => $menu));
    }
}