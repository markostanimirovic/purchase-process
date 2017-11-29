<?php

namespace controller;


use common\base\BaseController;

class SupplierController extends BaseController
{
    public function indexAction()
    {

    }

    public function insertAction()
    {
        $menu = $this->render('menu/main_menu.php');
        echo $this->render('supplier/insert.php', array('menu' => $menu));
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {
        
    }
}