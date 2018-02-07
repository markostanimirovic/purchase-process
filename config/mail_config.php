<?php
return array(
    'name' => 'Informacioni sistem za upravljanje procesom nabavke',
    'host' => 'smtp.gmail.com',
    'username' => 'purchase.process.app@gmail.com',
    'password' => 'marko.stanimirovic',
    'secure' => 'ssl',
    'port' => 465,
    'smtpOptions' => array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    )
);