<?php

namespace helper;

require ROOT . 'lib/PHPMailer/PHPMailer.php';
require ROOT . 'lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{

    public static function sendMail(string $address, string $subject, string $body): bool
    {
        $mailConfiguration = require(ROOT . 'config/mail_config.php');

        $phpMailer = new PHPMailer();
        $phpMailer->isSMTP();
        $phpMailer->SMTPAuth = true;
        $phpMailer->Host = $mailConfiguration['host'];
        $phpMailer->Username = $mailConfiguration['username'];
        $phpMailer->Password = $mailConfiguration['password'];
        $phpMailer->SMTPSecure = $mailConfiguration['secure'];
        $phpMailer->Port = $mailConfiguration['port'];
        $phpMailer->SMTPOptions = $mailConfiguration['smtpOptions'];

        $phpMailer->setFrom($mailConfiguration['username'], $mailConfiguration['name']);
        $phpMailer->addAddress($address);
        $phpMailer->Subject = $subject;
        $phpMailer->isHTML(true);
        $phpMailer->Body = $body;

        try {
            if ($phpMailer->send()) {
                return true;
            }
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }
}