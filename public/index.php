<?php
session_start();

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'helper/init.php';
require_once ROOT . 'helper/template_helper.php';

use common\FrontController;

$frontController = new FrontController();
$frontController->run();