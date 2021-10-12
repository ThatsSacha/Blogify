<?php

namespace App\Service;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailService {
    public function send(array $data = null, string $sendTo, string $replyTo = null, string $subject, string $body) {
        $mail = new PHPMailer(true);

        try {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
        
            $mail->setFrom('no-reply@blogify.sacha-cohen.fr', 'Blogify');
            $mail->addAddress($sendTo);

            if ($replyTo !== null) {
                $mail->addReplyTo($data['mail']);
            }
        
            $mail->isHTML(true);
            $mail ->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $body;
        
            if ($mail->send()) {
                echo 'Message has been sent';
            } else {
                echo 'Error while sending mail';
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}