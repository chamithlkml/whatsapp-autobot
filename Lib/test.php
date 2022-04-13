<?php
require_once './Mailer.php';

$mailer = new Mailer();
$mailer->sendMail('info@resthousetangalle.com', 'chamith@namefeeder.com', 'chamithlkml@gmail.com', 'My Subject', '<h3>Test Message</h3>');