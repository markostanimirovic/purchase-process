<?php
session_start();

define('ROOT', __DIR__ . '/../');

require_once ROOT . 'common/init.php';
require_once ROOT . 'common/template_helper.php';

use common\FrontController;

$frontController = new FrontController();
$frontController->run();


//require ROOT . 'lib/PHPMailer/PHPMailer.php';
//require ROOT . 'lib/PHPMailer/SMTP.php';
//use PHPMailer\PHPMailer\PHPMailer;
//
//$phpMailer = new PHPMailer();
//
//$phpMailer->isSMTP();
//
//$phpMailer->Host = 'smtp.gmail.com';
//
//$phpMailer->SMTPAuth = true;
//
//$phpMailer->Username = 'purchase.process.app@gmail.com';
//
//$phpMailer->Password = 'marko.stanimirovic';
//
//$phpMailer->SMTPOptions = array(
//    'ssl' => array(
//        'verify_peer' => false,
//        'verify_peer_name' => false,
//        'allow_self_signed' => true
//    )
//);
//
////$phpMailer->SMTPDebug = 2;
//
//$phpMailer->SMTPSecure = 'ssl';
//
//$phpMailer->Port = 465;
//
//$phpMailer->Subject = 'Hello Edis';
//
//$phpMailer->Body = 'Hello Edison! How are you?';
//
//$phpMailer->setFrom('purchase.process.app@gmail.com', 'Purchase process application');
//
//$phpMailer->addAddress('markostanimirovic95@gmail.com');
//
//if($phpMailer->send()) {
//    echo 'sent';
//} else {
//    echo 'not sent';
//}
