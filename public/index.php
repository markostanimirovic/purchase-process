<?php
session_start();

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'config/init.php';
require_once ROOT . 'common/template_helper.php';

use common\FrontController;

$frontController = new FrontController();
$frontController->run();