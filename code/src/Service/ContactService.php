<?php

namespace App\Service;

use Exception;

class ContactService {
    private $mailTemplateService;
    private $mailService;

    public function __construct(MailTemplateService $mailTemplateService, MailService $mailService)
    {
        $this->mailTemplateService = $mailTemplateService;
        $this->mailService = $mailService;
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

        $this->mailService->send(
            $data,
            'contact@sacha-cohen.fr',
            $data['mail'],
            'Demande de contact',
            $this->mailTemplateService->getContactTemplate($data)
        );
    }
}