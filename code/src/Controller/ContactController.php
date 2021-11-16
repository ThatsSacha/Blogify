<?php

namespace App\Controller;

use App\Model\Class\Superglobals;
use Exception;
use App\Service\ContactService;
use App\Service\MailService;
use App\Service\MailTemplateService;

class ContactController {
    private $data;
    private string $url;
    private string $method;
    private $result;
    private ContactService $contactService;
    private $superglobals;

    public function __construct(string $url, $data = null) {
        $this->superglobals = new Superglobals();
        $this->contactService = new ContactService(
            new MailTemplateService(),
            new MailService()
        );
        $this->data = $data;
        $this->url = $url;
        $this->method = $this->superglobals->get_SERVER('REQUEST_METHOD');
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'POST'])) {
            if ($this->url === '/send-message') {
                $this->sendMessage();
            }
        } else {
            $this->result = [
                'type' => 'error',
                'status' => 405,
                'message' => 'This method is not allowed'
            ];
        }
    }

    private function sendMessage(): void {
        try {
            $this->contactService->sendMessage($this->data);
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => $e->getCode() ? $e->getCode() : 400,
                'message' => $e->getMessage()
            ];
        }
    }

    public function loadResult() {
        return $this->result;
    }
}