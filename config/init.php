<?php

define('ROOT_TEMPLATE', ROOT . 'view/');

define('CONTROLLER_NAMESPACE', 'controller\\');
define('MODEL_NAMESPACE', 'model\\');
define('REPOSITORY_NAMESPACE', 'modelRepository\\');

define('DEFAULT_CONTROLLER', 'Index');
define('ERROR_CONTROLLER', 'Error');
define('DEFAULT_ACTION', 'index');

spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    $filePath = ROOT . $file;

    if (file_exists($filePath)) {
        require $filePath;
        return;
    }

    $directories = ['common',  'controller', 'login', 'model', 'modelRepository'];

    foreach ($directories as $dir) {
        $filePath = ROOT . $dir . '/' . $file;
        if (file_exists($filePath)) {
            require $filePath;
            return;
        }
    }
});