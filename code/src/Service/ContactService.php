<?php

namespace App\Service;

use Exception;
use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ContactService {
    private $mailTemplateService;

    public function __construct(MailTemplateService $mailTemplateService)
    {
        $this->mailTemplateService = $mailTemplateService;
    }

    public function sendMessage(array $data) {
        $mandatoryFields = ['firstName', 'lastName', 'mail', 'message'];

        foreach($mandatoryFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception('Tous les champs sont requis');
            }
        }
        
        $data['firstName'] = ucfirst(strtolower($data['firstName']));
        $data['lastName'] = strtoupper($data['lastName']);
        $data['subject'] = empty($data['subject']) ? $data['subject'] = 'Simple demande de contact' : $data['subject'];
        $data['message'] = nl2br($data['message']);

        $this->send($data);
    }

    public function send(array $data) {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.ionos.fr';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@blogify.sacha-cohen.fr';
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 587;
        
            $mail->setFrom('no-reply@blogify.sacha-cohen.fr', 'Blogify');
            $mail->addAddress('contact@sacha-cohen.fr');
            $mail->addReplyTo($data['mail']);
        
            $mail->isHTML(true);
            $mail ->CharSet = 'UTF-8'; 
            $mail->Subject = 'Demande de contact';
            $mail->Body    = $this->mailTemplateService->getContactTemplate($data);
        
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