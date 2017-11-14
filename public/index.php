<?php

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'config/init.php';

use common\FrontController;

$frontController = new FrontController();
$frontController->run();