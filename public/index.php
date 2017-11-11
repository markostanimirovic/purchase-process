<?php

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'config/init.php';

use common\FrontController;

$frontController = new FrontController();
$frontController->run();

//$p = new \model\Product();
//$p->setName('Marko');
//$p->setUnit('Markovic');
//$p->setPrice(1);
//$p->save();