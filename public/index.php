<?php

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'config/init.php';
require_once ROOT . 'common/TemplateHelper.php';

use common\FrontController;

$frontController = new FrontController();
$frontController->run();