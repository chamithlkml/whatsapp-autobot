<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once (__DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php');
require_once (__DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once (__DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php');

class Mailer
{
    public function sendMail($from, $to, $cc, $subject, $message)
    {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USERNAME;                     //SMTP username
            $mail->Password   = MAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

//            //Recipients
            $mail->setFrom($from, MAIL_FROM_NAME);
            $mail->addAddress($to);               //Name is optional
            $mail->addReplyTo($from, MAIL_FROM_NAME);
            $mail->addCC($cc);

//            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();

        } catch (\Exception $e) {

            Logger::error($e->getMessage());
            Logger::error($e->getTraceAsString());
            Logger::error("Mail error: ".$mail->ErrorInfo);
        }
    }
}